<?php

namespace Drupal\local_development\EventSubscriber;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\csp\Csp;
use Drupal\csp\CspEvents;
use Drupal\csp\Event\PolicyAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Alter CSP policy for infographic revision files.
 */
class ResponseCspSubscriber implements EventSubscriberInterface {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * Alter CSP policy for infographic revision files.
   */
  public function __construct(
    RouteMatchInterface $route_match
  ) {
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[CspEvents::POLICY_ALTER] = ['onCspPolicyAlter'];

    return $events;
  }

  /**
   * Alter CSP policy for infographic revision files.
   */
  public function onCspPolicyAlter(PolicyAlterEvent $alterEvent) {
    $policy = $alterEvent->getPolicy();
    $policy->appendDirective('script-src', [Csp::POLICY_ANY]);
    $policy->appendDirective('img-src', [Csp::POLICY_ANY]);
    $policy->appendDirective('connect-src', [Csp::POLICY_ANY]);
  }

}
