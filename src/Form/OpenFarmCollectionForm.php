<?php

namespace Drupal\open_farm\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\open_farm\Service\OpenFarmDataServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm\Service\OpenFarmServiceInterface;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class OpenFarmCollectionForm.
 */
class OpenFarmCollectionForm extends FormBase {
  private $animalId;

  /**
   * Drupal\open_farm\Service\OpenFarmServiceInterface definition.
   *
   * @var \Drupal\open_farm\Service\OpenFarmServiceInterface
   */
  protected $openFarmManagerService;
  protected $openFarmDataService;
  /**
   * Constructs a new OpenFarmCollectionForm object.
   */
  public function __construct(
    OpenFarmServiceInterface $openFarmManagerService, OpenFarmDataServiceInterface $openFarmDataService
  ) {
    $this->openFarmManagerService = $openFarmManagerService;
    $this->openFarmDataService = $openFarmDataService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('open_farm.manager_service'),
        $container->get('open_farm.data_manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'open_farm_collection_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

      $date = new DrupalDateTime();

      $needle = 'morning';
      if ($date->format('H') > "12"){
          $needle = 'afternoon';
      }

    $form['open_farm_collection'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('General Farm Collection Form'),
    );
    $periods = $this->openFarmManagerService->getTaxonomyTerms('periods');

    $form['open_farm_collection']['period'] = array(
        '#type' => 'select',
        '#title' => $this->t('Period'),
        '#options' => $periods,
        '#default_value' => [array_search($needle, $periods)],
    );
    $form['open_farm_collection']['collect_date'] = array(
        '#title' => t('Collection date'),
        '#type' => 'datetime',
        '#date_date_element' => 'date',
        '#date_time_element' => 'none',
        '#default_value' => DrupalDateTime::createFromTimestamp(time()),
        '#required' => TRUE,
    );
    $form['open_farm_collection']['amount_tag'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Anmial Tag'),
        '#required' => TRUE,
        '#suffix' => '<div id=animal-tag></div>',
        '#ajax' => array(
            'callback' => '::checkTag',
            'event' => 'change',
            'progress' => array(
                'type' => 'throbber',
                'message' => 'searching...',
            ),
        ),
    );

    $form['open_farm_collection']['amount_collected'] = array(
        '#type' => 'number',
        '#title' => $this->t('Amount'),
        '#description' => $this->t('Item collected (number of eggs, litres of milk e.t.c)'),
        '#required' => TRUE,
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $animalTag = $form_state->getValue('amount_tag');
    $animal = $this->openFarmManagerService->getAnimalByTag($animalTag);

    if (empty($animal)){
        $form_state->setError($form, $this->t('Invalid Animal Tag.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $collectionDate = $form_state->getValue('collect_date');
      $period = $form_state->getValue('period');
      $animalTag = $form_state->getValue('amount_tag');
      $animal = $this->openFarmManagerService->getAnimalByTag($animalTag);
      $amount = $form_state->getValue('amount_collected');
      $id = current($animal)->id();
      $data['type'] = 'milking_data';
      $data['title'] = $animalTag;
      $data['status'] = 1;
      $data['amount'] = $amount;
      $data['animal'] = ['target_id' => $id];
      $data['period'] = ['target_id' => $period];
      $data['collection_date'] = [$collectionDate->format('Y-m-d')];

      //$this->openFarmManagerService->createNode($data);

      $this->openFarmDataService->addDataValue([
          'animal_tag' => $animalTag,
          'period' => $period,
          'data_element' => 'Milk',
          'date_collected' => $collectionDate->format('Y-m-d'),
          'amount' => $amount
      ]);
  }
  public function checkTag(array $form, FormStateInterface $form_state) {
      $errorMsg = "";
      $animalTag = $form_state->getValue('amount_tag');
      $animal = $this->openFarmManagerService->getAnimalByTag($animalTag);
      if (empty($animal)){
          $errorMsg = "Animal Tag ".$animalTag." does not exist: ";
      }
      else{
          $id = current($animal)->id();
          $form_state->set('animal_id', "".$id);
      }
      $ajax_response = new AjaxResponse();

      $ajax_response->addCommand(new HtmlCommand('#animal-tag', $errorMsg ));
      return $ajax_response;
  }
}
