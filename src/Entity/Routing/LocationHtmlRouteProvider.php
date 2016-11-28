<?php

namespace Drupal\locationentity\Entity\Routing;

use Drupal\Core\Entity\Controller\EntityController;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Drupal\locationentity\Controller\LocationAdminController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides routes for location entities.
 */
class LocationHtmlRouteProvider implements EntityRouteProviderInterface {

  /**
   * The location entity definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $entityType;

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $this->entityType = $entity_type;
    $prefix = 'entity.locationentity.';

    $collection = new RouteCollection();
    $collection->add($prefix . 'add_form', $this->addFormRoute());
    $collection->add($prefix . 'add_page', $this->addPageRoute());
    $collection->add($prefix . 'canonical', $this->canonicalRoute());
    $collection->add($prefix . 'collection', $this->collectionRoute());
    $collection->add($prefix . 'delete_form', $this->deleteFormRoute());
    $collection->add($prefix . 'edit_form', $this->editFormRoute());

    return $collection;
  }

  /**
   * Returns the 'add_form' route.
   *
   * @return \Symfony\Component\Routing\Route
   *   The generated route.
   */
  protected function addFormRoute() {
    return (new Route($this->getPath('add-form')))
      ->setDefaults([
        '_controller' => LocationAdminController::class .'::addForm',
        '_title_callback' => LocationAdminController::class .'::addFormTitle',
      ])
      ->setRequirement('_entity_create_access', 'locationentity:{locationentity_type}')
      ->setOptions([
        'parameters' => [
          'locationentity' => ['type' => 'entity:locationentity'],
          'locationentity_type' => ['type' => 'entity:locationentity_type'],
        ],
        '_admin_route' => TRUE
      ]);
  }

  /**
   * Returns the 'add_page' route.
   *
   * @return \Symfony\Component\Routing\Route
   *   The generated route.
   */
  protected function addPageRoute() {
    return (new Route($this->getPath('add-page')))
      ->setDefaults([
        '_controller' => LocationAdminController::class .'::addPage',
        '_title' => 'Add location',
      ])
      ->setRequirement('_entity_create_access', 'locationentity')
      ->setOption('_admin_route', TRUE);
  }

  /**
   * Returns the 'canonical' route.
   *
   * @return \Symfony\Component\Routing\Route
   *   The generated route.
   */
  protected function canonicalRoute() {
    return (new Route($this->getPath('canonical')))
      ->setDefaults([
        '_entity_view' => 'locationentity.full',
        '_title_callback' => EntityController::class . '::title',
      ])
      ->setRequirements([
        '_entity_access' => 'locationentity.view',
        'locationentity' => '\d+',
      ])
      ->setOptions([
        'parameters' => [
          'locationentity' => ['type' => 'entity:locationentity'],
        ],
      ]);
  }

  /**
   * Returns the 'collection' route.
   *
   * @return \Symfony\Component\Routing\Route
   *   The generated route.
   */
  protected function collectionRoute() {
    return (new Route($this->getPath('collection')))
      ->setDefaults([
        '_entity_list' => 'locationentity',
        '_title' => 'Locations',
      ])
      ->setRequirement('_permission', 'access locationentity overview')
      ->setOption('_admin_route', TRUE);
  }

  /**
   * Returns the 'delete_form' route.
   *
   * @return \Symfony\Component\Routing\Route
   *   The generated route.
   */
  protected function deleteFormRoute() {
    return (new Route($this->getPath('delete-form')))
      ->setDefaults([
        '_entity_form' => "locationentity.delete",
        '_title_callback' => EntityController::class . '::deleteTitle',
      ])
      ->setRequirements([
        '_entity_access' => 'locationentity.delete',
        'locationentity' => '\d+',
      ])
      ->setOptions([
        'parameters' => [
          'locationentity' => ['type' => 'entity:locationentity'],
        ],
      ]);
  }

  /**
   * Returns the 'edit_form' route.
   *
   * @return \Symfony\Component\Routing\Route
   *   The generated route.
   */
  protected function editFormRoute() {
    return (new Route($this->getPath('edit-form')))
      ->setDefaults([
        '_entity_form' => "locationentity.edit",
        '_title_callback' => EntityController::class . '::editTitle',
      ])
      ->setRequirements([
        '_entity_access' => 'locationentity.update',
        'locationentity' => '\d+',
      ])
      ->setOptions([
        'parameters' => [
          'locationentity' => ['type' => 'entity:locationentity'],
        ],
      ]);
  }

  /**
   * Gets the path for a given link type.
   *
   * @param string $link_template
   *   The link type key.
   *
   * @return string
   *   The path for this link.
   */
  protected function getPath($link_template) {
    return $this->entityType->getLinkTemplate($link_template);
  }

}
