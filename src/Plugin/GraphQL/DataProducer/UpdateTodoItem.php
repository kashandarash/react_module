<?php

namespace Drupal\react_module\Plugin\GraphQL\DataProducer;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Drupal\react_module\Entity\ReactModuleTodoItem;
use Drupal\react_module\Wrappers\TodoResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates a new todo item entity.
 *
 * @DataProducer(
 *   id = "update_todo_item",
 *   name = @Translation("Update react_module_todo_item"),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("Entity react_module_todo_item")
 *   ),
 *   consumes = {
 *     "data" = @ContextDefinition("any",
 *       label = @Translation("Data: id, title and completed")
 *     )
 *   }
 * )
 */
class UpdateTodoItem extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('entity_type.manager')
    );
  }

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
   *   Entity type manager.
   *
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, protected AccountInterface $currentUser, protected EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Resolve method.
   */
  public function resolve(array $data): TodoResponse {

    // Sleep to see "Loading..." message (solo for testing).
    //sleep(1);

    $response = new TodoResponse();
    if ($this->currentUser->hasPermission("edit todo item")) {
      try {
        // ID in our case is Drupal UUID.
        $entity = $this->entityTypeManager->getStorage('react_module_todo_item')->loadByProperties([
          'uid' => $this->currentUser->id(),
          'uuid' => $data['id'],
        ]);
        $entity = reset($entity);
        if (!$entity instanceof ReactModuleTodoItem) {
          throw new \Exception('Entity does not exists.');
        }
        // Update item.
        $entity->set('label', $data['title']);
        $entity->set('status', (bool) $data['completed']);
        $entity->save();

        // Set entity to response object and return.
        $response->setItem($entity);
        return $response;
      }
      catch (\Exception $e) {
        // It would be better to write something to logs from Exception.

        // Return response.
        $response->addViolation($this->t("Can't save entity"));
        return $response;
      }
    }

    $response->addViolation(
      $this->t('You do not have permissions to create item.')
    );

    return $response;
  }

}
