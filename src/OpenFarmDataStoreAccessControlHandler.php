<?php

namespace Drupal\open_farm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Open farm data store entity.
 *
 * @see \Drupal\open_farm\Entity\OpenFarmDataStore.
 */
class OpenFarmDataStoreAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\open_farm\Entity\OpenFarmDataStoreInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished open farm data store entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published open farm data store entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit open farm data store entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete open farm data store entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add open farm data store entities');
  }

}
