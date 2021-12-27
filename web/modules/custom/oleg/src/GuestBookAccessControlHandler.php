<?php

namespace Drupal\oleg;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the review entity.
 *
 * @see \Drupal\oleg\Entity\GuestBook.
 */
class GuestBookAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view review entity');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit review entity');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete review entity');
    }
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   *
   * Separate from the chekAccess because the entity does not yet exist, it will
   * creat during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add review entity');
  }

}
