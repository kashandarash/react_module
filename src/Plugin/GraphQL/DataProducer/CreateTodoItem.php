<?php

namespace Drupal\react_module\Plugin\GraphQL\DataProducer;

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
 *   id = "create_todo_item",
 *   name = @Translation("Create react_module_todo_item"),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("Entity react_module_todo_item")
 *   ),
 *   consumes = {
 *     "data" = @ContextDefinition("any",
 *       label = @Translation("Data: title and completed")
 *     )
 *   }
 * )
 */
class CreateTodoItem extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
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
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, protected AccountInterface $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Resolve method.
   */
  public function resolve(array $data): TodoResponse {

    // Sleep to see "Loading..." message (solo for testing).
    //sleep(1);

    $response = new TodoResponse();
    if ($this->currentUser->hasPermission("create todo item")) {
      try {
        $values = [
          'label' => $data['title'],
          'status' => $data['completed'] ?? FALSE,
          'uid' => $this->currentUser->id(),
        ];
        $entity = ReactModuleTodoItem::create($values);
        $entity->save();
        $response->setItem($entity);
        return $response;
      }
      catch (\Exception $e) {
        // it would be better to write something to logs.
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
