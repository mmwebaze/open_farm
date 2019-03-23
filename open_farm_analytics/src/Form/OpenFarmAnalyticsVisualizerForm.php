<?php

namespace Drupal\open_farm_analytics\Form;

use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm\Plugin\Period\PeriodManager;
use Drupal\open_farm\Service\OpenFarmServiceInterface;

class OpenFarmAnalyticsVisualizerForm extends FormBase
{
    protected $periodManager;
    protected $openFarmManagerService;
    protected $openFarmAnalyticService;

    public function getFormId(){
        return 'open_farm_analytics_visualizer_form';
    }

    /**
     * OpenFarmAnalyticsVisualizerForm constructor.
     * @param PeriodManager $periodManager
     * @param OpenFarmServiceInterface $openFarmManagerService
     * @param OpenFarmAnalyticsInterface $openFarmAnalyticService
     */
    public function __construct(PeriodManager $periodManager, OpenFarmServiceInterface $openFarmManagerService,
                                OpenFarmAnalyticsInterface $openFarmAnalyticService){
        $this->periodManager = $periodManager;
        $this->openFarmManagerService = $openFarmManagerService;
        $this->openFarmAnalyticService = $openFarmAnalyticService;
    }
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('plugin.manager.periods'),
            $container->get('open_farm.manager_service'),
            $container->get('open_farm_analytics.manager_service')
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
        if (!empty($femaleGender)){
            $animals = $this->openFarmManagerService->getAnimals(['data_store' => 1, 'animal_gender' => array_search('female', $femaleGender)]);
        }
        $tagId = '';
        foreach ($animals as $key => $animal){
            $tagId = $animal->getTagId();
            $animalTags[$tagId] = $tagId;

        }

        $storedConfigs = $this->openFarmAnalyticService->getChartConfigs([]);
        $storedVisualizations = array('0' => 'select');

        foreach ($storedConfigs as $storedConfig){
            $storedVisualizations[$storedConfig->uuid] = $storedConfig->id.'->'.$storedConfig->chart_title.' - '.$storedConfig->uuid;
        }
        //print_r($storedVisualizations); die();

        $form['dimensions'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
            '#prefix' => '<div class="flex-container">',
            '#suffix' => '</div>',
        );

       /* $form['title_modal'] = array(
            '#type' => 'markup',
            '#markup' =>'<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Open modal</button>',
        );*/
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
            '#default_value' => $tagId,
            '#required' => TRUE,
            '#prefix' => '<div class="data_element">',
            '#suffix' => '</div>',
        );
        $form['dimensions']['chart_types'] = array(
            '#type' => 'select',
            '#title' => $this->t('Chart Type'),
            '#options' => ['column' => 'Column', 'line' => 'Line', 'pie' => 'Pie'],
            '#default_value' => ['column'],
            '#required' => TRUE,
            '#prefix' => '<div class="data_element">',
            '#suffix' => '</div>',
        );
        $form['stored_visuals'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
            '#prefix' => '<div class="flex-container">',
            '#suffix' => '</div>',
        );
        $form['stored_visuals']['stored_charts'] = array(
            '#type' => 'select',
            '#title' => $this->t('Stored visualizations'),
            '#options' => $storedVisualizations,
            //'#default_value' => 'milk',
            //'#required' => TRUE,
            '#prefix' => '<div class="stored_charts">',
            '#suffix' => '</div>',
        );

        $form['buttons_pane'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
            '#prefix' => '<div class="buttons_pane">',
            '#suffix' => '</div>',
        );
        $form['buttons_pane']['save_visual'] = array(
            '#type' => 'button',
            '#value' => $this->t('Save this for later'),
            '#attributes' => array(
                'data-toggle' => array(
                    'modal'
                ),
                'data-target' => array(
                    '#myModal'
                )
            ),
            '#ajax' => array(
                'callback' => '::saveVisualization',
                'event' => 'click',
                'progress' => array(
                    'type' => 'throbber',
                    'message' => $this->t('Saving...'),
                ),
            ),
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
    public function saveVisualization(){
        $response = new AjaxResponse();
        return $response;
    }
    public function submitForm(array &$form, FormStateInterface $form_state){

    }
}