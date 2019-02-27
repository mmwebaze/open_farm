<?php

namespace Drupal\open_farm\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Open farm data store edit forms.
 *
 * @ingroup open_farm
 */
class OpenFarmDataStoreForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\open_farm\Entity\OpenFarmDataStore */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Open farm data store.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Open farm data store.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.open_farm_data_store.canonical', ['open_farm_data_store' => $entity->id()]);
  }

}
