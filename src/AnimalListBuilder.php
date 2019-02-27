<?php

namespace Drupal\open_farm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Animal entities.
 *
 * @ingroup open_farm
 */
class AnimalListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['tag_id'] = $this->t('Tag ID');
    $header['animal_gender'] = $this->t('Gender');
    $header['animal_type'] = $this->t('Type');
    $header['status'] = $this->t('Available');
    $header['active'] = $this->t('Active');
    $header['data_store'] = $this->t('Data Store');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\open_farm\Entity\Animal */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.animal.edit_form',
      ['animal' => $entity->id()]
    );
    $row['tag_id'] = $entity->getTagId();
    $row['animal_gender'] = $entity->getGender();
    $row['animal_type'] = $entity->getAnimalType();
    $row['status'] = $entity->isPublished();
    $row['active'] = $entity->isActive();
    $row['data_store'] = $entity->getDataStore()->id();
    return $row + parent::buildRow($entity);
  }
}
