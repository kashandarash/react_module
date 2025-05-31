<?php

namespace Drupal\react_module\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a todo item entity type.
 */
interface ReactModuleTodoItemInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
