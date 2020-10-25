<?php

namespace Drupal\allowed_languages;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\UserInterface;

class AllowedLanguagesManager {

  protected $currentUser;

  protected $entityTypeManager;

  public function __construct(AccountProxyInterface $current_user, EntityTypeManagerInterface $entity_type_manager) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
  }

  public function currentUserEntity() {
    return $this->userEntityFromProxy($this->currentUser);
  }

  public function userEntityFromProxy(AccountProxyInterface $account) {
    return $this->entityTypeManager
      ->getStorage('user')
      ->load($account->id());
  }

  /**
   * Get the allowed languages for the specified user.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user to get allowed languages for.
   *
   * @return array
   *   An array of allowed language ids.
   */
  public function assignedLanguages(UserInterface $user = null) {
    if ($user === NULL) {
      $user = $this->currentUserEntity();
    }

    $language_values = [];

    // Make sure the field exists before attempting to get languages.
    if (!$user->hasField('allowed_languages')) {
      return $language_values;
    }

    // Get the id of each referenced language.
    foreach ($user->get('allowed_languages')->getValue() as $item) {
      $language_values[] = $item['target_id'];
    }

    return $language_values;
  }

  /**
   * Checks if the user is allowed to translate the specified language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language to check for.
   * @param \Drupal\user\UserInterface $user
   *   The user to check.
   *
   * @return bool
   *   If the user is allowed to or not.
   */
  public function hasPermissionForLanguage(LanguageInterface $language, UserInterface $user = null) {
    if ($user === NULL) {
      $user = $this->currentUserEntity();
    }

    // Bypass the access check if the user has permission to translate all languages.
    if ($user->hasPermission('translate all languages')) {
      return TRUE;
    }

    $allowed_languages = $this->assignedLanguages($user);
    return in_array($language->getId(), $allowed_languages);
  }

}
