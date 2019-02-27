<?php

namespace Drupal\open_farm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Open farm data store entities.
 *
 * @ingroup open_farm
 */
interface OpenFarmDataStoreInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Open farm data store name.
   *
   * @return string
   *   Name of the Open farm data store.
   */
  public function getName();

  /**
   * Sets the Open farm data store name.
   *
   * @param string $name
   *   The Open farm data store name.
   *
   * @return \Drupal\open_farm\Entity\OpenFarmDataStoreInterface
   *   The called Open farm data store entity.
   */
  public function setName($name);

  /**
   * Gets the Open farm data store creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Open farm data store.
   */
  public function getCreatedTime();

  /**
   * Sets the Open farm data store creation timestamp.
   *
   * @param int $timestamp
   *   The Open farm data store creation timestamp.
   *
   * @return \Drupal\open_farm\Entity\OpenFarmDataStoreInterface
   *   The called Open farm data store entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Open farm data store published status indicator.
   *
   * Unpublished Open farm data store are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Open farm data store is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Open farm data store.
   *
   * @param bool $published
   *   TRUE to set this Open farm data store to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\open_farm\Entity\OpenFarmDataStoreInterface
   *   The called Open farm data store entity.
   */
  public function setPublished($published);

}
