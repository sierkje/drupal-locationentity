<?php

namespace Drupal\locationentity\Messenger;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;
use Drupal\locationentity\LocationMessengerInterface;

/**
 * Defines a passive location messenger.
 *
 * This implementation will store and return the messages set during a session,
 * but won't actually display any messages to the user by itself.
 */
class PassiveMessenger implements LocationMessengerInterface {

  /**
   * The errors set during this session.
   *
   * @var array
   */
  protected $errors = [];

  /**
   * The errors set during this session.
   *
   * @var array
   */
  protected $status_messages = [];

  /**
   * The errors set during this session.
   *
   * @var array
   */
  protected $warnings = [];

  /**
   * {@inheritdoc}
   */
  public function isOutputEnabled() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function addError($error, $repeat = FALSE) {
    $error = $this->getMessageArray($error);

    if ($repeat || !in_array($error, $this->errors)) {
      $this->errors[] = $error;
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors() {
    return $this->errors;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteErrors() {
    $this->errors = [];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addStatusMessage($status_message, $repeat = FALSE) {
    $status_message = $this->getMessageArray($status_message);

    if ($repeat || !in_array($status_message, $this->status_messages)) {
      $this->status_messages[] = $status_message;
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStatusMessages() {
    return $this->status_messages;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteStatusMessages() {
    $this->status_messages = [];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addWarning($warning, $repeat = FALSE) {
    $warning = $this->getMessageArray($warning);

    if ($repeat || !in_array($warning, $this->warnings)) {
      $this->warnings[] = $warning;
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWarnings() {
    return $this->warnings;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteWarnings() {
    $this->warnings = [];

    return $this;
  }

  /**
   * Returns the storage array element for a message.
   *
   * @param string|\Drupal\Component\Render\MarkupInterface $message
   *   The message
   *
   * @return array
   *   If a safe string is givenConvert strings which are safe to the simplest Markup objects.

   */
  protected function getMessageArray($message) {
    $safe = FALSE;

    if ($message instanceof MarkupInterface) {
      // Mark as safe.
      $safe = TRUE;

      // Convert strings which are safe to the simplest Markup objects.
      if (!($message instanceof Markup)) {
        $message = Markup::create((string) $message);
      }
    }

    return ['safe' => $safe, 'message' => $message];

  }

}
