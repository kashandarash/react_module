<?php

namespace Drupal\react_module\Plugin\GraphQL\DataProducer;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Drupal\react_module\Wrappers\QueryConnection;
use GraphQL\Error\Error;
use GraphQL\Error\UserError;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @DataProducer(
 *   id = "query_todo_items",
 *   name = @Translation("Load query_todo_items"),
 *   description = @Translation("Loads a list of query_todo_items."),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("Create query_todo_items connection")
 *   ),
 *   consumes = {
 *     "offset" = @ContextDefinition("integer",
 *       label = @Translation("Offset"),
 *       required = FALSE
 *     ),
 *     "limit" = @ContextDefinition("integer",
 *       label = @Translation("Limit"),
 *       required = FALSE
 *     )
 *   }
 * )
 */
class QueryTodoItems extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  const MAX_LIMIT = 1000;

  /**
   * Items constructor.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $pluginId
   *   The plugin id.
   * @param mixed $pluginDefinition
   *   The plugin definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity Type Manager.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   */
  public function __construct(array $configuration, $pluginId, $pluginDefinition, private EntityTypeManagerInterface $entityTypeManager, private AccountInterface $currentUser) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * Resolve method.
   *
   * @param int $offset
   * @param int $limit
   * @param \Drupal\Core\Cache\RefinableCacheableDependencyInterface $metadata
   *
   * @return \Drupal\react_module\Wrappers\QueryConnection
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \GraphQL\Error\Error
   */
  public function resolve(int $offset, int $limit, RefinableCacheableDependencyInterface $metadata): QueryConnection {
    if ($limit > static::MAX_LIMIT) {
      throw new UserError(sprintf('Exceeded maximum query limit: %s.', static::MAX_LIMIT));
    }

    // Throw error every once is a while to see error message.
    // Write graphql error, so we can get it in the response.
    //if (rand(0, 6) === 0) {
    //  throw new Error('Some error (((');
    //}

    $storage = $this->entityTypeManager->getStorage('react_module_todo_item');
    $entityType = $storage->getEntityType();
    $query = $storage->getQuery()
      ->condition('uid', $this->currentUser->id())
      ->accessCheck();

    $query->range($offset, $limit);

    $metadata->addCacheTags($entityType->getListCacheTags());
    $metadata->addCacheContexts($entityType->getListCacheContexts());

    // Sleep for 1 sec to see "Loading..." message (only for test).
    //sleep(1);

    return new QueryConnection($query);
  }

}
