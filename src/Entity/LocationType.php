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
 *       "html" = "Drupal\locationentity\Routing\LocationTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer locationentity_type",
 *   bundle_of = "locationentity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/locationentity/types/add",
 *     "canonical" = "/admin/structure/locationentity/types/manage/{locationentity_type}",
 *     "collection" = "/admin/structure/locationentity/types",
 *     "delete-form" = "/admin/structure/locationentity/types/manage/{locationentity_type}/delete",
 *     "edit-form" = "/admin/structure/locationentity/types/manage/{locationentity_type}/edit",
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
