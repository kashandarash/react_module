<?php

namespace Drupal\react_module;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the todo item entity type.
 */
class ReactModuleTodoItemAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    // Allow all for admin user.
    if ($account->hasPermission('administer todo item')) {
      return AccessResult::allowed();
    }

    return match ($operation) {
      'view' => AccessResult::allowedIfHasPermission($account, 'view todo item'),
      'update' => AccessResult::allowedIfHasPermission($account, 'update todo item'),
      'delete' => AccessResult::allowedIfHasPermission($account, 'delete todo item'),
      default => AccessResult::neutral(),
    };

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions(
      $account,
      ['create todo item', 'administer todo item'],
      'OR',
    );
  }

}
