<?php

namespace Drupal\open_farm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Open farm data store entities.
 *
 * @ingroup open_farm
 */
class OpenFarmDataStoreListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Open farm data store ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\open_farm\Entity\OpenFarmDataStore */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.open_farm_data_store.edit_form',
      ['open_farm_data_store' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
