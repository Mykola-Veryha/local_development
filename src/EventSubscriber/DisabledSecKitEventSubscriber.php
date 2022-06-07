<?php

namespace Drupal\local_development\EventSubscriber;

use Drupal\seckit\EventSubscriber\SecKitEventSubscriber;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Subscribing an event.
 */
class DisabledSecKitEventSubscriber extends SecKitEventSubscriber {

  /**
   * Checking are not needed on the local environment.
   */
  public function onKernelRequest(RequestEvent $event) {
  }

}
