<?php

namespace Drupal\locationentity\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * The location type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $typeStorage;

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
   * @param \Drupal\Core\Entity\EntityStorageInterface $type_storage
   *   The location type storage.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   *   The entity form builder service.
   */
  public function __construct(EntityStorageInterface $storage, EntityStorageInterface $type_storage, TranslationInterface $string_translation, EntityFormBuilderInterface $entity_form_builder) {
    $this->storage = $storage;
    $this->typeStorage = $type_storage;
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
    /** @var \Drupal\Core\Entity\EntityStorageInterface $type_storage */
    $type_storage = $entity_type_manager->getStorage('locationentity_type');
    /** @var \Drupal\Core\StringTranslation\TranslationInterface $string_translation */
    $string_translation = $container->get('string_translation');
    /** @var \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder */
    $entity_form_builder = $container->get('entity.form_builder');

    return new static($storage, $type_storage, $string_translation, $entity_form_builder);
  }

  /**
   * Displays links to the location creation forms for types.
   *
   * Presents a creation form if only one type is available.
   *
   * @return array
   *   A render array with links for the types that can be added, or (if only
   *   one type is available) a form array with the creation form for that type.
   */
  public function addPage() {
    $types = $this->typeStorage->loadMultiple();

    if ($types && count($types) == 1) {
      $type = reset($types);

      return $this->addForm($type);
    }

    if (count($types) === 0) {
      $link_text = $this->t('Add a new location type');
      $link_route_name = 'entity.locationentity_type.add_form';

      return [
        '#markup' => $this->t('No location types are available. @link.', [
          '@link' => Link::createFromRoute($link_text, $link_route_name),
        ]),
      ];
    }

    return ['#theme' => 'locationentity_add_list', '#content' => $types];
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
   * Returns the creation form page title for for locations of given type.
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

}
