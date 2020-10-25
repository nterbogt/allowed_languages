<?php

namespace Drupal\allowed_languages\Access;

use Drupal\allowed_languages\AllowedLanguagesManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Allowed languages access check base class.
 */
abstract class AccessCheckBase implements AccessInterface {

  /**
   * Drupal entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  protected $allowedLanguagesManager;

  /**
   * AccessCheck constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Drupal entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AllowedLanguagesManager $allowed_languages_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->allowedLanguagesManager = $allowed_languages_manager;
  }

  /**
   * Loads a user entity based on account proxy object.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account proxy used to load the full user entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\user\UserInterface|null
   *   User entity or NULL.
   */
  protected function loadUserEntityFromAccountProxy(AccountInterface $account) {
    return $this->entityTypeManager
      ->getStorage('user')
      ->load($account->id());
  }

}
