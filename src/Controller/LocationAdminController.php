<?php

namespace Drupal\locationentity\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityAccessControlHandlerInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Url;
use Drupal\locationentity\LocationTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Returns responses for location routes.
 */
class LocationAdminController implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The location storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * The location access control handler.
   *
   * @var \Drupal\Core\Entity\EntityAccessControlHandlerInterface
   */
  protected $accessControlHandler;

  /**
   * The location type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $typeStorage;

  /**
   * The location type definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $typeDefinition;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The entity form builder service.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface
   */
  protected $entityFormBuilder;

  /**
   * Constructs a new LocationController object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The location storage.
   * @param \Drupal\Core\Entity\EntityAccessControlHandlerInterface $access_control_handler
   *   The location access control handler.
   * @param \Drupal\Core\Entity\EntityStorageInterface $type_storage
   *   The location type storage.
   * @param \Drupal\Core\Entity\EntityTypeInterface $type_definition
   *   The location type definition.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   *   The entity form builder service.
   */
  public function __construct(EntityStorageInterface $storage, EntityAccessControlHandlerInterface $access_control_handler, EntityStorageInterface $type_storage, EntityTypeInterface $type_definition, RendererInterface $renderer, TranslationInterface $string_translation, EntityFormBuilderInterface $entity_form_builder) {
    $this->storage = $storage;
    $this->typeStorage = $type_storage;
    $this->typeDefinition = $type_definition;
    $this->stringTranslation = $string_translation;
    $this->entityFormBuilder = $entity_form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = $container->get('entity_type.manager');
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $entity_type_manager->getStorage('locationentity');
    /** @var \Drupal\Core\Entity\EntityAccessControlHandlerInterface $access_control_handler */
    $access_control_handler = $entity_type_manager->getAccessControlHandler('locationentity');
    /** @var \Drupal\Core\Entity\EntityStorageInterface $type_storage */
    $type_storage = $entity_type_manager->getStorage('locationentity_type');
    /** @var \Drupal\Core\Entity\EntityTypeInterface $type_definition */
    $type_definition = $entity_type_manager->getDefinition('locationentity_type');
    /** @var \Drupal\Core\Render\RendererInterface $renderer */
    $renderer = $container->get('renderer');
    /** @var \Drupal\Core\StringTranslation\TranslationInterface $string_translation */
    $string_translation = $container->get('string_translation');
    /** @var \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder */
    $entity_form_builder = $container->get('entity.form_builder');

    return new static($storage, $access_control_handler, $type_storage, $type_definition, $renderer, $string_translation, $entity_form_builder);
  }

  /**
   * Presents the creation form for locations of given type.
   *
   * @param EntityInterface $locationentity_type
   *   The entity representing the type of the new location.
   *
   * @return array
   *   A form array as expected by drupal_render().
   */
  public function addForm(EntityInterface $locationentity_type) {
    $entity = $this->storage->create(['type' => $locationentity_type->id()]);

    return $this->entityFormBuilder->getForm($entity);
  }

  /**
   * Returns the creation form page title for locations of given type.
   *
   * @param EntityInterface $locationentity_type
   *   The entity representing the type of the new location.
   *
   * @return string
   *   A page title.
   */
  public function addFormTitle(EntityInterface $locationentity_type) {
    return $this->t('Create location of type @label',
      ['@label' => $locationentity_type->label()]
    );
  }

  /**
   * Displays links to the location creation forms for types.
   *
   * Redirects to the creation form if only one type is available.
   *
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   *   A render array with links for the types that can be added, or (if only
   *   one type is available) a RedirectResponse to the form for that type.
   */
  public function addPage() {
    $build = [
      '#theme' => 'locationentity_add_list',
      '#cache' => ['tags' => $this->typeDefinition->getListCacheTags()],
    ];

    $content = [];

    // Only use types the user has access to.
    foreach ($this->typeStorage->loadMultiple() as $type) {
      /** @var \Drupal\locationentity\LocationTypeInterface $type */
      $access = $this->createAccess($type);
      if ($access->isAllowed()) {
        $content[$type->id()] = $type;
      }
      $this->renderer->addCacheableDependency($build, $access);
    }

    // Bypass the listing if only one type is available.
    if (count($content) == 1) {
      $type = array_shift($content);

      $name = 'entity.locationentity.add_form';
      $parameters = ['locationentity_type' => $type->id()];
      $options = ['absolute' => TRUE];

      return new RedirectResponse(Url::fromRoute($name, $parameters, $options));
    }

    $build['#content'] = $content;

    return $build;
  }

  /**
   * Checks location create access.
   *
   * @param \Drupal\locationentity\LocationTypeInterface $locationentity_type
   *   The entity representing the type of the new location.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   An AccessResultInterface object.
   */
  protected function createAccess(LocationTypeInterface $locationentity_type) {
    return $this->accessControlHandler
      ->createAccess($locationentity_type->id(), NULL, [], TRUE);
  }

}
