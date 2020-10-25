<?php

namespace Drupal\allowed_languages\Access;

use Drupal\allowed_languages\AllowedLanguagesManager;
use Drupal\Core\Routing\Access\AccessInterface;

/**
 * Allowed languages access check base class.
 */
abstract class AccessCheckBase implements AccessInterface {

  protected $allowedLanguagesManager;

  /**
   * AccessCheck constructor.
   */
  public function __construct(AllowedLanguagesManager $allowed_languages_manager) {
    $this->allowedLanguagesManager = $allowed_languages_manager;
  }

}
