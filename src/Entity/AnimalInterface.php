<?php

namespace Drupal\open_farm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Animal entities.
 *
 * @ingroup open_farm
 */
interface AnimalInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Animal name.
   *
   * @return string
   *   Name of the Animal.
   */
  public function getName();

  /**
   * Sets the Animal name.
   *
   * @param string $name
   *   The Animal name.
   *
   * @return \Drupal\open_farm\Entity\AnimalInterface
   *   The called Animal entity.
   */
  public function setName($name);

  /**
   * Gets the Animal creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Animal.
   */
  public function getCreatedTime();

  /**
   * Sets the Animal creation timestamp.
   *
   * @param int $timestamp
   *   The Animal creation timestamp.
   *
   * @return \Drupal\open_farm\Entity\AnimalInterface
   *   The called Animal entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Animal published status indicator.
   *
   * Unpublished Animal are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Animal is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Animal.
   *
   * @param bool $published
   *   TRUE to set this Animal to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\open_farm\Entity\AnimalInterface
   *   The called Animal entity.
   */
  public function setPublished($published);

}
