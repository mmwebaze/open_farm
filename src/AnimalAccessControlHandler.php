<?php

namespace Drupal\open_farm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Animal entity.
 *
 * @see \Drupal\open_farm\Entity\Animal.
 */
class AnimalAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\open_farm\Entity\AnimalInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished animal entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published animal entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit animal entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete animal entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add animal entities');
  }

}
