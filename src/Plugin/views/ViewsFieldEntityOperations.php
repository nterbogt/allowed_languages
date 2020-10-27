<?php

namespace Drupal\allowed_languages\Plugin\views;

use Drupal\views\Plugin\views\field\EntityOperations;
use Drupal\views\ResultRow;

class ViewsFieldEntityOperations extends EntityOperations {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $build = parent::render($values);

    $user = \Drupal::currentUser();
    $this->renderer->addCacheableDependency($build, $user);

    return $build;
  }

}
