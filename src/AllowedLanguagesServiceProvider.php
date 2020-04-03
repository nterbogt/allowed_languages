<?php

namespace Drupal\allowed_languages;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies the language manager service.
 */
class AllowedLanguagesServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides language_manager class to test domain language negotiation.
    // Adds entity_type.manager service as an additional argument.
    $definition = $container->getDefinition('content_translation.delete_access');
    $definition->setClass('Drupal\allowed_languages\Access\AllowedLanguagesDeleteAccess');
  }

}
