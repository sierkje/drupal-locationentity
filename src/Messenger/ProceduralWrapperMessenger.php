<?php

namespace Drupal\locationentity\Messenger;

use Drupal\locationentity\LocationMessengerInterface;

/**
 * Provides a wrapper for drupal_set_message() and drupal_get_messages().
 *
 * If instantiated in a context where the functions drupal_set_message() and
 * drupal_get_messages() are both available, messages will be passed from and to
 * those functions. Otherwise, in a context where either function is not
 * available, this messenger will act as a 'passive messenger', i.e. messages
 * are stored and returned, but not actively displayed to the user.
 */
class ProceduralWrapperMessenger extends PassiveMessenger implements LocationMessengerInterface {

  /**
   * Indicates whether drupal_set_message() and drupal_get_messages() exist.
   *
   * @var bool|null
   *
   * @see \Drupal\locationentity\Messenger\ProceduralWrapperMessenger::drupalSetMessageExists()
   */
  protected $drupal_set_message_exists = NULL;

  /**
   * {@inheritdoc}
   */
  public function addError($error, $repeat = FALSE) {
    if ($this->drupalSetMessageExists()) {
      return drupal_set_message($error, 'error', FALSE);
    }

    return parent::addError($error, $repeat);
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors() {
    if ($this->drupalSetMessageExists()) {
      return drupal_get_messages('error', FALSE);
    }

    return parent::getErrors();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteErrors() {
    if ($this->drupalSetMessageExists()) {
      return drupal_get_messages('error');
    }

    return parent::deleteErrors();
  }

  /**
   * {@inheritdoc}
   */
  public function addStatusMessage($status_message, $repeat = FALSE) {
    if ($this->drupalSetMessageExists()) {
      return drupal_set_message($status_message, 'status', FALSE);
    }

    return parent::addStatusMessage($status_message, $repeat);
  }

  /**
   * {@inheritdoc}
   */
  public function getStatusMessages() {
    if ($this->drupalSetMessageExists()) {
      return drupal_get_messages('status', FALSE);
    }

    return parent::getStatusMessages();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteStatusMessages() {
    if ($this->drupalSetMessageExists()) {
      return drupal_get_messages('status');
    }

    return parent::deleteStatusMessages();
  }

  /**
   * {@inheritdoc}
   */
  public function addWarning($warning, $repeat = FALSE) {
    if ($this->drupalSetMessageExists()) {
      return drupal_set_message($warning, 'warning', $repeat);
    }

    return parent::addWarning($warning, $repeat);
  }

  /**
   * {@inheritdoc}
   */
  public function getWarnings() {
    if ($this->drupalSetMessageExists()) {
      return drupal_get_messages('warning', FALSE);
    }

    return parent::getWarnings();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteWarnings() {
    if ($this->drupalSetMessageExists()) {
      return drupal_get_messages('warning');
    }

    return parent::deleteWarnings();
  }

  /**
   * Ensures that drupal_set_message() and drupal_get_messages() are available.
   *
   * @return bool
   *   TRUE if drupal_set_message() and drupal_get_messages() are available.
   */
  protected function drupalSetMessageExists() {
    if ($this->drupal_set_message_exists === NULL) {
      $this->drupal_set_message_exists = FALSE;

      if (function_exists('drupal_set_message')) {
        if (function_exists('drupal_get_messages')) {
          $this->drupal_set_message_exists =  TRUE;
        }
      }
    }

    return (bool) $this->drupal_set_message_exists;
  }

}
