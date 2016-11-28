<?php

namespace Drupal\locationentity\Entity\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Location entity.
 *
 * @see \Drupal\locationentity\Entity\Location.
 */
class LocationAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($account->hasPermission('bypass locationentity access')) {
      return AccessResult::allowedIfHasPermission($account, 'bypass locationentity access');
    }

    if ($account->hasPermission('administer locationentity')) {
      return AccessResult::allowedIfHasPermission($account, 'administer locationentity');
    }

    /** @var \Drupal\locationentity\LocationInterface $entity */
    switch ($operation) {
      case 'view':
      if (!$entity->isPublished()) {
          if ($entity->getOwnerId() === $account->id()) {
            if ($account->hasPermission('view own unpublished locationentity')) {
              return AccessResult::allowedIfHasPermission($account, 'view own unpublished locationentity');
            }
          }

          return AccessResult::allowedIfHasPermission($account, 'view any unpublished locationentity');
        }

        return AccessResult::allowedIfHasPermission($account, 'view published locationentity');

      case 'update':
        if ($entity->getOwnerId() === $account->id()) {
          if ($account->hasPermission('edit own locationentity')) {
            return AccessResult::allowedIfHasPermission($account, 'edit own locationentity');
          }
        }

        return AccessResult::allowedIfHasPermission($account, 'edit any locationentity');

      case 'delete':
        if ($entity->getOwnerId() === $account->id()) {
          if ($account->hasPermission('delete own locationentity')) {
            return AccessResult::allowedIfHasPermission($account, 'delete own locationentity');
          }
        }

        return AccessResult::allowedIfHasPermission($account, 'delete any locationentity');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    if ($account->hasPermission('bypass locationentity access')) {
      return AccessResult::allowedIfHasPermission($account, 'bypass locationentity access');
    }

    if ($account->hasPermission('administer locationentity')) {
      return AccessResult::allowedIfHasPermission($account, 'administer locationentity');
    }

    return AccessResult::allowedIfHasPermission($account, 'add locationentity entities');
  }

}
