<?php

namespace Drupal\open_farm_analytics\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
        );
        $form['visualizer'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
        );
        $form['visualizer']['display'] = array(
            '#markup' => '<div id="chart"></div>'
        );
        $form['#attached']['library'][] = 'open_farm_analytics/visualizer';

        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {

        parent::validateForm($form, $form_state);
    }
    public function submitForm(array &$form, FormStateInterface $form_state) {

    }
}