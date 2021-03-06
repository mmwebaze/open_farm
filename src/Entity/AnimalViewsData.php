<?php

namespace Drupal\open_farm\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Animal entities.
 */
class AnimalViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
