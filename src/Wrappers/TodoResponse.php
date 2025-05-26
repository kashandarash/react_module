<?php

namespace Drupal\react_module\Wrappers;

use Drupal\graphql\GraphQL\Response\Response;
use Drupal\react_module\Entity\ReactModuleTodoItem;

/**
 * Type of response used when a todo_item is returned.
 */
class TodoResponse extends Response {

  /**
   * The item to be served.
   *
   * @var \Drupal\react_module\Entity\ReactModuleTodoItem|null
   */
  protected ?ReactModuleTodoItem $item;

  /**
   * Sets the content.
   *
   * @param \Drupal\react_module\Entity\ReactModuleTodoItem|null $item
   *   The article to be served.
   */
  public function setItem(?ReactModuleTodoItem $item): void {
    $this->item = $item;
  }

  /**
   * Gets the item to be served.
   *
   * @return \Drupal\react_module\Entity\ReactModuleTodoItem|null
   *   The item to be served.
   */
  public function item(): ?ReactModuleTodoItem {
    return $this->item;
  }

}
