<?php

namespace Drupal\allowed_languages\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\content_translation\Access\ContentTranslationDeleteAccess;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;

class AllowedLanguagesDeleteAccess extends ContentTranslationDeleteAccess {

  public function checkAccess(ContentEntityInterface $entity) {
    $result = parent::checkAccess($entity);

    $user = \Drupal::currentUser()->getAccount();
    if ($result->isAllowed() && !$this->userIsAllowedToTranslateLanguage($user, $entity->language())) {
      $result = AccessResult::forbidden();
    }

    return $result;
  }

  protected function userIsAllowedToTranslateLanguage(AccountInterface $account, LanguageInterface $language) {
    // Bypass the access check if the user has permission to translate all languages.
    if ($account->hasPermission('translate all languages')) {
      return TRUE;
    }

    $user = User::load($account->id());
    $allowed_languages = allowed_languages_get_allowed_languages_for_user($user);
    return in_array($language->getId(), $allowed_languages);
  }

}
