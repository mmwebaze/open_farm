<?php

namespace Drupal\open_farm_analytics\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class OpenFarmAnalyticsManagementForm.
 */
class OpenFarmAnalyticsManagementForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
      return [
          'open_farm_analytics.config'
      ];
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'open_farm_analytics_management_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
      $plugin_manager = \Drupal::service('plugin.manager.periods');
      $plugin_definitions = $plugin_manager->getDefinitions();

    $form['open_form_analytics'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('General Data Export Settings'),
    );
    $form['submit_start_export'] = [
      '#type' => 'submit',
      '#value' => $this->t('Start Export'),
      '#submit' => array('::startExportHandler'),
    ];
    $form['submit_save_settings'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save Settings'),
        '#submit' => array('::saveSettingsHandler'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function saveSettingsHandler(array &$form, FormStateInterface $form_state) {
die('Save settings');
  }
  public function startExportHandler(array &$form, FormStateInterface $form_state){
      die('start export');
  }
}
