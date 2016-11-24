<?php

namespace Drupal\locationentity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\locationentity\LocationTypeInterface;

/**
 * Defines the Location type entity.
 *
 * @ConfigEntityType(
 *   id = "locationentity_type",
 *   label = @Translation("Location type"),
 *   handlers = {
 *     "list_builder" = "Drupal\locationentity\LocationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\locationentity\Form\LocationTypeForm",
 *       "edit" = "Drupal\locationentity\Form\LocationTypeForm",
 *       "delete" = "Drupal\locationentity\Form\LocationTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\locationentity\LocationTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "locationentity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "locationentity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/locationentity/locationentity_type/{locationentity_type}",
 *     "add-form" = "/admin/structure/locationentity/locationentity_type/add",
 *     "edit-form" = "/admin/structure/locationentity/locationentity_type/{locationentity_type}/edit",
 *     "delete-form" = "/admin/structure/locationentity/locationentity_type/{locationentity_type}/delete",
 *     "collection" = "/admin/structure/locationentity/locationentity_type"
 *   }
 * )
 */
class LocationType extends ConfigEntityBundleBase implements LocationTypeInterface {
  /**
   * The Location type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Location type label.
   *
   * @var string
   */
  protected $label;

}
