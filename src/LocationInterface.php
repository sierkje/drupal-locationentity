<?php

namespace Drupal\locationentity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Location entities.
 *
 * @ingroup locationentity
 */
interface LocationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Location type.
   *
   * @return string
   *   The Location type.
   */
  public function getType();

  /**
   * Gets the Location name.
   *
   * @return string
   *   Name of the Location.
   */
  public function getName();

  /**
   * Sets the Location name.
   *
   * @param string $name
   *   The Location name.
   *
   * @return \Drupal\locationentity\LocationInterface
   *   The called Location entity.
   */
  public function setName($name);

  /**
   * Gets the Location creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Location.
   */
  public function getCreatedTime();

  /**
   * Sets the Location creation timestamp.
   *
   * @param int $timestamp
   *   The Location creation timestamp.
   *
   * @return \Drupal\locationentity\LocationInterface
   *   The called Location entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Location published status indicator.
   *
   * Unpublished Location are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Location is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Location.
   *
   * @param bool $published
   *   TRUE to set this Location to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\locationentity\LocationInterface
   *   The called Location entity.
   */
  public function setPublished($published);

}
