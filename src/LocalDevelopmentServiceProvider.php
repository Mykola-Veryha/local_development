<?php

namespace Drupal\local_development;

use Drupal\Core\Cache\NullBackendFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\local_development\EventSubscriber\DisabledSecKitEventSubscriber;
use Symfony\Component\DependencyInjection\Definition;
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
    if ($container->hasDefinition('seckit.subscriber')) {
      $definition = $container->getDefinition('seckit.subscriber');
      $definition->setClass(DisabledSecKitEventSubscriber::class);
    }
    $container->setDefinition('cache.backend.null', new Definition(NullBackendFactory::class));

    // In order to avoid CORS policy errors, website’s resources
    // can be accessed by any origin.
    $cors_config = $container->getParameter('cors.config');
    $cors_config['allowedOrigins'] = ['*'];
    $cors_config['allowedHeaders'] = ['*'];
    $cors_config['allowedMethods'] = ['*'];
    $cors_config['enabled'] = TRUE;
    $cors_config['supportsCredentials'] = TRUE;
    $container->setParameter('cors.config', $cors_config);
  }

}
