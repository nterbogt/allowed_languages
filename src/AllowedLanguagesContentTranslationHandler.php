<?php

namespace Drupal\allowed_languages;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\content_translation\ContentTranslationHandler;

class AllowedLanguagesContentTranslationHandler extends ContentTranslationHandler {

  public function getTranslationAccess(EntityInterface $entity, $op) {
    return AccessResult::forbidden();
  }

}
