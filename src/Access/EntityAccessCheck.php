<?php

namespace Drupal\allowed_languages\Access;

use Drupal\allowed_languages\AllowedLanguagesManagerInterface;
use Drupal\allowed_languages\UrlLanguageService;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;

/**
 * Allowed languages entity access check.
 */
class EntityAccessCheck extends AccessCheckBase {

  /**
   * Allowed access url language service.
   *
   * @var \Drupal\allowed_languages\UrlLanguageService
   */
  private $urlLanguageService;

  /**
   * AccessCheck constructor.
   *
   * @param \Drupal\allowed_languages\AllowedLanguagesManagerInterface $allowed_languages_manager
   *   The allowed language manager service.
   * @param \Drupal\allowed_languages\UrlLanguageService $url_language_service
   *   Allowed access url language service.
   */
  public function __construct(
    AllowedLanguagesManagerInterface $allowed_languages_manager,
    UrlLanguageService $url_language_service
  ) {
    parent::__construct($allowed_languages_manager);

    $this->urlLanguageService = $url_language_service;
  }

  /**
   * Allowed languages entity access check.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The current route.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Result from the access check.
   */
  public function access(Route $route, RouteMatchInterface $route_match, AccountInterface $account) {
    // Split the entity type and the operation.
    $requirement = $route->getRequirement('_entity_access');
    list($entity_type, $operation) = explode('.', $requirement);

    // Only perform the access check when performing update/delete operations.
    if (!in_array($operation, ['update', 'delete'])) {
      return AccessResult::allowed();
    }

    $parameters = $route_match->getParameters();

    // If the entity type parameter is missing, let other modules
    // take care of the access check then.
    if (!$parameters->has($entity_type)) {
      return AccessResult::allowed();
    }

    $entity = $parameters->get($entity_type);

    // Only check the access for entities that are content entities since this
    // module only support translatable content entities.
    // Also exclude non-translatable content types from
    // allowed languages' access control.
    if (!$entity instanceof ContentEntityInterface || !$entity->isTranslatable()) {
      return AccessResult::allowed();
    }

    // @todo Remove usage of the url language service when a better solution
    // is found.
    $language = $this->urlLanguageService->getUrlLanguage() ?: $entity->language();
    $user = $this->allowedLanguagesManager->userEntityFromProxy($account);

    if ($this->allowedLanguagesManager->hasPermissionForLanguage($language, $user)) {
      return AccessResult::allowed();
    }

    // Access check failed so do not allow the user to translate the specified
    // language.
    return AccessResult::forbidden();
  }

}
