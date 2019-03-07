<?php

namespace Drupal\open_farm_analytics\Form;

use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm\Plugin\Period\PeriodManager;
use Drupal\open_farm\Service\OpenFarmServiceInterface;

class OpenFarmAnalyticsVisualizerForm extends FormBase
{
    protected $periodManager;
    protected $openFarmManagerService;

    public function getFormId(){
        return 'open_farm_analytics_visualizer_form';
    }

    /**
     * OpenFarmAnalyticsVisualizerForm constructor.
     * @param PeriodManager $periodManager
     * @param OpenFarmServiceInterface $openFarmManagerService
     */
    public function __construct(PeriodManager $periodManager, OpenFarmServiceInterface $openFarmManagerService){
        $this->periodManager = $periodManager;
        $this->openFarmManagerService = $openFarmManagerService;
    }
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('plugin.manager.periods'),
            $container->get('open_farm.manager_service')
        );
    }
    public function buildForm(array $form, FormStateInterface $form_state) {
        $animalTags = array();
        $periodDefinitions = $this->periodManager->getDefinitions();
        $periods = array();
        foreach ($periodDefinitions as $pluginId => $periodDefinition){
            $periods[$pluginId] = $periodDefinition['name'];

        }
        $femaleGender = $this->openFarmManagerService->getTaxonomyTerms('gender', 'female');

        $animals = $this->openFarmManagerService->getAnimals(['data_store' => 1, 'animal_gender' => array_search('female', $femaleGender)]);

        foreach ($animals as $key => $animal){
            $animalTags[$animal->getTagId()] = $animal->getTagId();
        }

        $form['dimensions'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
            '#prefix' => '<div class="flex-container">',
            '#suffix' => '</div>',
        );
        $form['dimensions']['de'] = array(
            '#type' => 'select',
            '#title' => $this->t('data'),
            '#options' => ['Milk' => 'Milk', 'Eggs' => 'Eggs'],
            '#default_value' => 'milk',
            '#required' => TRUE,
            '#prefix' => '<div class="data_element">',
            '#suffix' => '</div>',
        );
        $form['dimensions']['pe'] = array(
            '#type' => 'select',
            '#title' => $this->t('Period'),
            '#options' => $periods,
            '#default_value' => 'last_3_months',
            '#required' => TRUE,
            '#prefix' => '<div class="data_element">',
            '#suffix' => '</div>',
        );
        $form['dimensions']['src'] = array(
            '#type' => 'select',
            '#multiple' => TRUE,
            '#title' => $this->t('Animal Tags'),
            '#options' => $animalTags,
            '#default_value' => '1234',
            '#required' => TRUE,
            '#prefix' => '<div class="data_element">',
            '#suffix' => '</div>',
        );

        $form['buttons_pane'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
            '#prefix' => '<div class="buttons_pane">',
            '#suffix' => '</div>',
        );
        $form['buttons_pane']['visualize'] = array(
            '#type' => 'button',
            '#value' => $this->t('Visualize'),
            '#ajax' => array(
                'callback' => '::generateVisualization',
                'event' => 'click',
                'progress' => array(
                    'type' => 'throbber',
                    'message' => $this->t('Processing ...'),
                ),
            ),
        );
        $form['visualizer'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
        );

        $form['visualizer']['display'] = array(
            '#markup' => '<div id="chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>'
        );
        $form['#attached']['library'][] = 'open_farm_analytics/visualizer';

        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {

        parent::validateForm($form, $form_state);
    }
    public function generateVisualization(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        $response->addCommand(new HtmlCommand('.result_message', 'update please'));

        /*$response->addCommand(
            new InvokeCommand('#chart', 'attr', array('data-chart', $data_str))
        );*/

        return $response;
    }
    public function submitForm(array &$form, FormStateInterface $form_state){

    }
}