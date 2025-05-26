<?php

namespace Drupal\react_module\Plugin\GraphQL\Schema;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\Plugin\GraphQL\Schema\SdlSchemaPluginBase;
use Drupal\react_module\Wrappers\QueryConnection;
use Drupal\react_module\Wrappers\TodoResponse;

/**
 * @Schema(
 *   id = "react_module_schema",
 *   name = "Schema for TODO items"
 * )
 */
class TodoItemsSchema extends SdlSchemaPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getResolverRegistry() {
    $builder = new ResolverBuilder();
    $registry = new ResolverRegistry();

    // Describe TodoItems for Query type.
    $registry->addFieldResolver('Query', 'TodoItems',
      $builder->produce('query_todo_items')
        ->map('offset', $builder->fromArgument('offset'))
        ->map('limit', $builder->fromArgument('limit'))
    );

    // Describe fields for TodoItems type.
    $registry->addFieldResolver('TodoItems', 'total',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->total();
      })
    );
    $registry->addFieldResolver('TodoItems', 'items',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->items();
      })
    );

    // Describe fields for TodoItem type.
    $registry->addFieldResolver('TodoItem', 'id',
      $builder->produce('entity_uuid')
        ->map('entity', $builder->fromParent())
    );
    $registry->addFieldResolver('TodoItem', 'title',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent()),
    );
    $registry->addFieldResolver('TodoItem', 'completed',
      $builder->produce('property_path')
        ->map('type', $builder->fromValue('entity:react_module_todo_item'))
        ->map('value', $builder->fromParent())
        ->map('path', $builder->fromValue('status.value')),
    );

    // Update item mutation.
    $registry->addFieldResolver('Mutation', 'UpdateTodoItem',
      $builder->produce('update_todo_item')
        ->map('data', $builder->fromArgument('data'))
    );

    // Create item mutation.
    $registry->addFieldResolver('Mutation', 'CreateTodoItem',
      $builder->produce('create_todo_item')
        ->map('data', $builder->fromArgument('data'))
    );

    // Describe fields for TodoItemResponse type.
    $registry->addFieldResolver('TodoItemResponse', 'item',
      $builder->callback(function (TodoResponse $response) {
        return $response->item();
      })
    );
    $registry->addFieldResolver('TodoItemResponse', 'errors',
      $builder->callback(function (TodoResponse $response) {
        return $response->getViolations();
      })
    );

    // Delete item mutation.
    $registry->addFieldResolver('Mutation', 'DeleteTodoItem',
      $builder->produce('delete_todo_item')
        ->map('data', $builder->fromArgument('data'))
    );

    return $registry;
  }

}
