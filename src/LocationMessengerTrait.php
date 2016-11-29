<?php

namespace Drupal\locationentity;

trait LocationMessengerTrait {

  /**
   * Sets a status message to display to the user.
   */
  protected function addStatusMessage($message, $repeat = FALSE) {
    if (function_exists('drupal_set_message')) {
      drupal_set_message($message, 'status', $repeat);
    }
  }

}
