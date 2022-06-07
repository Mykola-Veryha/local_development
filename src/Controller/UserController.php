<?php

namespace Drupal\local_development\Controller;

use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * User controller.
 */
class UserController extends ControllerBase {

  /**
   * The CSRF token generator.
   *
   * @var \Drupal\Core\Access\CsrfTokenGenerator
   */
  private CsrfTokenGenerator $csrfToken;

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  private UserStorageInterface $userStorage;

  /**
   * User controller.
   */
  public function __construct(
    CsrfTokenGenerator $csrf_token,
    UserStorageInterface $user_storage
  ) {
    $this->csrfToken = $csrf_token;
    $this->userStorage = $user_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): UserController {
    return new static(
      $container->get('csrf_token'),
      $container->get('entity_type.manager')->getStorage('user')
    );
  }

  /**
   * Account information.
   */
  public function account(): JsonResponse {
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->userStorage->load($this->currentUser()->id());
    $user_values = [
      'id' => $user->id(),
      'uid' => $user->id(),
      'uuid' => $user->uuid(),
      'roles' => $user->getRoles(),
      'firstName' => '',
      'lastName' => '',
      'isDelegate' => false,
      'title' => 'Mr. Title',
      'country' => '',
      'token' => [
        'csrf' => $this->csrfToken->get('rest'),
      ],
    ];
    if ($user->hasField('field_first_name')) {
      $user_values['firstName'] = $user->get('field_first_name')->getString();
    }
    if ($user->hasField('field_last_name')) {
      $user_values['lastName'] = $user->get('field_last_name')->getString();
    }
    if ($user->hasField('field_country')) {
      $user_values['country'] = $user->get('field_country')->getString();
    }
    if (method_exists($user, 'isDelegate')) {
      $user_values['isDelegate'] = $user->isDelegate();
    }

    return JsonResponse::create([
      'user' => $user_values,
    ]);
  }

}
