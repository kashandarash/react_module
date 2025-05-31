<?php

namespace Drupal\react_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an app block.
 *
 * @Block(
 *   id = "react_module_example",
 *   admin_label = @Translation("React App Example Block"),
 *   category = @Translation("react_module")
 * )
 */
class ReactAppBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Please use DI here ;)
    $site_url = \Drupal::request()->getScheme() . '://' . \Drupal::request()->getHost();

    // Creating block renderable array and attaching library with react app.
    // This way we can use this block anywhere on the site.
    $build['content'] = [
      '#markup' => '<div id="root-react"></div>',
      '#attached' => [
        'library' => 'react_module/app',
        'drupalSettings' => [
          'reactApp' => [
            'list_title' => $this->t('Todo list'),
            'graphql_url' => $site_url . '/graphql/todo',
          ],
        ],
      ],
    ];

    return $build;
  }

}
