<?php

namespace Drupal\react_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "react_module_example",
 *   admin_label = @Translation("React Example"),
 *   category = @Translation("react_module")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => '<div id="root-react"></div>',
      '#attached' => [
        'library' => 'react_module/app',
        'drupalSettings' => [
          'reactApp' => [
            'list_title' => $this->t('Todo list'),
            'graphql_url' => 'https://clear.ddev.site/graphql/todo',
          ],
        ],
      ],

    ];
    return $build;
  }

}
