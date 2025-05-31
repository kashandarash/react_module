<?php

namespace Drupal\react_module\Plugin\GraphQL\DataProducer;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Delete todo item entity.
 *
 * @DataProducer(
 *   id = "delete_todo_item",
 *   name = @Translation("Delete react_module_todo_item"),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("Delete entity react_module_todo_item")
 *   ),
 *   consumes = {
 *     "data" = @ContextDefinition("string",
 *       label = @Translation("ID: UUID of the entity")
 *     )
 *   }
 * )
 */
class DeleteTodoItem extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  /**
   * CreateArticle constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity Type Manager.
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, protected AccountInterface $currentUser, protected EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Resolve method.
   */
  public function resolve(string $id): bool {
    // Sleep to see "Loading..." message (solo for testing).
    //sleep(1);

    if (!$this->currentUser->hasPermission("delete todo item")) {
      return FALSE;
    }

    $entity = $this->entityTypeManager->getStorage('react_module_todo_item')->loadByProperties([
      'uid' => $this->currentUser->id(),
      'uuid' => $id,
    ]);
    $entity = reset($entity);
    if (!$entity instanceof EntityInterface) {
      return FALSE;
    }

    try {
      $entity->delete();
      return TRUE;
    }
    catch (\Exception $e) {
      return FALSE;
    }
  }

}
