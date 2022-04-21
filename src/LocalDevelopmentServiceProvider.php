<?php

namespace Drupal\local_development;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modifies the pfizer_login event subscriber.
 */
class LocalDevelopmentServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container): void {
    if ($container->hasDefinition('current_user')) {
      $definition = $container->getDefinition('current_user');
      $definition->setClass(GlobalAccountProxy::class);
      $definition->setArguments([
        new Reference('event_dispatcher'),
        new Reference('state'),
      ]);
    }
  }

}
