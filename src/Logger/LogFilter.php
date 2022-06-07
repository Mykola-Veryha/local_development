<?php

namespace Drupal\local_development\Logger;

use Drupal\dblog\Logger\DbLog;

/**
 * Exclude some unwanted log messages.
 */
class LogFilter extends DbLog {

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    $chanel = $context['channel'] ?? '';
    $excluded_channels = ['pfizer_performance'];
    if (!in_array($chanel, $excluded_channels, TRUE)) {
      parent::log($level, $message, $context);
    }
  }

}
