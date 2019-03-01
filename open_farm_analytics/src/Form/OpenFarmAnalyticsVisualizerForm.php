<?php

namespace Drupal\open_farm_analytics\Form;

use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\open_farm_analytics\Chart\Model\Data;

class OpenFarmAnalyticsVisualizerForm extends FormBase
{
    public function getFormId(){
        return 'open_farm_analytics_visualizer_form';
    }
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['dimensions'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
        );
        $form['dimensions']['de'] = array(
            '#type' => 'select',
            '#title' => $this->t('data'),
            '#options' => ['milk' => 'Milk', 'eggs' => 'Eggs'],
            '#default_value' => 'milk',
            '#required' => TRUE,
        );
        $form['dimensions']['pe'] = array(
            '#type' => 'select',
            '#title' => $this->t('Period'),
            '#options' => ['this_month' => 'This Month', 'last_month' => 'Last Month'],
            '#default_value' => 'this_month',
            '#required' => TRUE,
        );
        $form['dimensions']['src'] = array(
            '#type' => 'select',
            '#title' => $this->t('Animal Tags'),
            '#options' => ['1234' => '1234', '12345' => '12345'],
            '#default_value' => '1234',
            '#required' => TRUE,
            '#ajax' => array(
                'callback' => '::getData',
                'event' => 'change',
                'progress' => array(
                    'type' => 'throbber',
                    'message' => 'searching...',
                ),
            ),
        );
        $form['dimensions']['visualize'] = [
            '#type' => 'button',
            '#value' => $this->t('Visualize'),
            //'#submit' => array('::startExportHandler'),
            '#ajax' => array(
                'callback' => '::generateVisualization',
                'event' => 'click',
                'progress' => array(
                    'type' => 'throbber',
                    'message' => $this->t('Processing ...'),
                ),
            ),
        ];
        $form['data'] = [
            '#type' => 'markup',
            '#markup' => '<div class="result_message" data-viz="fddffdfgdgfdg">fgdg</div>'
        ];
        $form['wizard_page'] = array(
            '#type' => 'hidden',
            '#value' => 2,
        );
        $form['visualizer'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
        );
        $form['visualizer']['display'] = array(
            '#markup' => '<div id="chart" data-chart="9809090"></div>'
        );
        $form['#attached']['library'][] = 'open_farm_analytics/visualizer';
        //$form['#attached']['drupalSettings']['open_farm_analytics']['chart_data']['d'] = $this->d;

        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {

        parent::validateForm($form, $form_state);
    }
    public function generateVisualization(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        $response->addCommand(new HtmlCommand('.result_message', 'update please'));


        return $response;
    }
    public function getData(array &$form, FormStateInterface $form_state){
        $response = new AjaxResponse();
        $data = new Data();
        $data->setChartType('bar');
        $data->addColumns(['1234', 12, 30, 40]);
        $data_str = json_encode($data);
        $response->addCommand(
            new InvokeCommand('#chart', 'attr', array('data-chart', $data_str))
        );

        //$form['#attached']['drupalSettings']['open_farm_analytics']['chart_data']['d'] = 'BBC_AND_CNN';
        return $response;
    }
    public function submitForm(array &$form, FormStateInterface $form_state){
        die('start export');
    }
}