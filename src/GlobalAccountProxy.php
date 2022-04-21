<?php

namespace Drupal\local_development;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\State\StateInterface;
use Drupal\local_development\Form\LocalDevelopmentConfigForm;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * A proxied implementation of AccountInterface.
 */
class GlobalAccountProxy extends AccountProxy {

  /**
   * The state key/value store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  private StateInterface $state;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    StateInterface $state
  ) {
    parent::__construct($eventDispatcher);
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccount(): AccountInterface {
    if ($this->state->get(LocalDevelopmentConfigForm::STATUS_CONFIG_NAME, FALSE)) {
      $active_uid = (int) $this->state->get(LocalDevelopmentConfigForm::USER_ID_CONFIG_NAME, 0);
      if (!empty($active_uid)) {
        $this->setAccount($this->loadUserEntity($active_uid));

        return $this->account;
      }
    }

    return parent::getAccount();
  }

  /**
   * {@inheritdoc}
   */
  public function id(): int {
    if (!isset($this->account)) {
      $this->getAccount();
    }

    return $this->id;
  }

}
