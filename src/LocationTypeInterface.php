<?php

namespace Drupal\locationentity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Location type entities.
 */
interface LocationTypeInterface extends ConfigEntityInterface {

  /**
   * Returns the description of this location type.
   *
   * @return string
   *   The description.
   */
  public function getDescription();

}
