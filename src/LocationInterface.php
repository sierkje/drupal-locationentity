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

  /**
   * Denotes that the location is published.
   */
  const LOCATION_IS_PUBLISHED = 1;

  /**
   * Denotes that the location is not published.
   */
  const LOCATION_IS_UNPUBLISHED = 0;

  /**
   * Returns the location type.
   *
   * @return string
   *   The machine-readable name of the type of the location.
   */
  public function getType();

  /**
   * Returns the location name.
   *
   * @return string
   *   The name of the location.
   */
  public function getName();

  /**
   * Sets the location name.
   *
   * @param string $name
   *   The name of the location.
   *
   * @return \Drupal\locationentity\LocationInterface
   *   This location entity object.
   */
  public function setName($name);

  /**
   * Returns the time that the location was first created.
   *
   * @return int
   *   The timestamp of the first entity save operation.
   */
  public function getCreatedTime();

  /**
   * Indicates whether the location is published or not.
   *
   * Unpublished locations are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the location is published, FALSE if it is unpublished.
   */
  public function isPublished();

  /**
   * Publish the location.
   *
   * @return \Drupal\locationentity\LocationInterface
   *   This location entity object.
   */
  public function publish();

  /**
   * Unpublish the location.
   *
   * @return \Drupal\locationentity\LocationInterface
   *   This location entity object.
   */
  public function unpublish();

}
