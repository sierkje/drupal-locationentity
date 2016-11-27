<?php

namespace Drupal\locationentity;

/**
 * Provides an interface for an injectable messenger service.
 *
 * The service this interface defines makes it possible to provide Drupal's
 * message setters and getters to unit-testable classes and methods. This
 * messenger service should be made available as 'locationentity.messenger'.
 *
 * Once possible, implementations of this interface should wrap the messenger
 * service provided by Drupal core, once that service is committed to core. For
 * more, consult the issue for Drupal core: https://www.drupal.org/node/2774931
 * @todo Use Drupal core messenger service to set/get messages.
 *
 * In the meantime, implementations should wrap the drupal_set_message() and
 * drupal_get_messages() procedural functions, if and when running in a context
 * where those functions are available. (In a context where those functions are
 * not available, messages set during a session should be stored otherwise,
 * ensuring that those messages can be returned by the getter methods.)
 * @todo Remove references to drupal_set_message() and drupal_get_messages().
 *
 * @see https://www.drupal.org/node/2774931
 * @see drupal_set_message()
 * @see drupal_get_messages()
 */
interface LocationMessengerInterface {

  /**
   * Indicates whether messages are displayed to the user.
   *
   * @return bool
   *   FALSE if messages are stored and returned, but not actually displayed to
   *   the user.
   */
  public function isOutputEnabled();

  /**
   * Sets an 'error' message to display to the user.
   *
   * @param string|\Drupal\Component\Render\MarkupInterface $error
   *   The translated error to be displayed to the user. For consistency with
   *   other messages, it should begin with a capital letter and end with a
   *   period.
   * @param bool $repeat
   *   (optional) If this is FALSE and the message is already set, then the
   *   message won't be repeated. Defaults to FALSE.
   *
   * @return $this
   *
   * @see \Drupal\locationentity\Messenger\LocationMessageWrapperInterface::TYPE_ERROR
   */
  public function addError($error, $repeat = FALSE);

  /**
   * Returns all 'error' messages.
   *
   * Wraps around drupal_get_messages(), if that function is available.
   *
   * @return string[]\Drupal\Component\Render\MarkupInterface[]
   *   An indexed array containing the errors that are set during this session,
   *   each error is an associative array with the following format:
   *   - safe: Boolean indicating whether the message has been marked as safe.
   *   - message: The message as string or Markup object.
   */
  public function getErrors();

  /**
   * Deletes all errors.
   *
   * @return $this
   */
  public function deleteErrors();

  /**
   * Sets a 'status' message to display to the user.
   *
   * @param string|\Drupal\Component\Render\MarkupInterface $status_message
   *   The translated status message to be displayed to the user. For
   *   consistency with other messages, it should begin with a capital letter
   *   and end with a period.
   * @param bool $repeat
   *   (optional) If this is FALSE and the message is already set, then the
   *   message won't be repeated. Defaults to FALSE.
   *
   * @return $this
   */
  public function addStatusMessage($status_message, $repeat = FALSE);

  /**
   * Returns all 'status' messages.
   *
   * @return string[]\Drupal\Component\Render\MarkupInterface[]
   *   An indexed array containing the status messages that are set during this
   *   session, each message is an associative array with the following format:
   *   - safe: Boolean indicating whether the message has been marked as safe.
   *   - message: The message as string or Markup object.
   *
   */
  public function getStatusMessages();

  /**
   * Deletes all status messages.
   *
   * @return $this
   */
  public function deleteStatusMessages();

  /**
   * Sets a 'warning' message to display to the user.
   *
   * @param string|\Drupal\Component\Render\MarkupInterface $warning
   *   The translated warning to be displayed to the user. For consistency with
   *   other messages, it should begin with a capital letter and end with a
   *   period.
   * @param bool $repeat
   *   (optional) If this is FALSE and the message is already set, then the
   *   message won't be repeated. Defaults to FALSE.
   *
   * @return $this
   */
  public function addWarning($warning, $repeat = FALSE);

  /**
   * Returns all 'warning' messages.
   *
   * @return array
   *   An indexed array containing the warnings that are set during this
   *   session, each warning is an associative array with the following format:
   *   - safe: Boolean indicating whether the message has been marked as safe.
   *   - message: The message as string or Markup object.
   */
  public function getWarnings();

  /**
   * Deletes all warnings.
   *
   * @return $this
   */
  public function deleteWarnings();

}
