<?php

namespace Drupal\locationentity\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\locationentity\LocationInterface;
use Drupal\user\UserInterface;

/**
 * Defines the location entity.
 *
 * @ingroup locationentity
 *
 * @ContentEntityType(
 *   id = "locationentity",
 *   bundle_entity_type = "locationentity_type",
 *   label = @Translation("Location"),
 *   label_singular = @Translation("location"),
 *   label_plural = @Translation("locations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count location",
 *     plural = "@count locations"
 *   ),
 *   bundle_label = @Translation("Location type"),
 *   handlers = {
 *     "storage" = "Drupal\locationentity\Entity\Storage\LocationSqlStorage",
 *     "storage_schema" = "Drupal\locationentity\Entity\Storage\LocationSqlStorageSchema",
 *     "view_builder" = "Drupal\locationentity\Entity\Builder\LocationViewBuilder",
 *     "list_builder" = "Drupal\locationentity\Entity\Builder\LocationListBuilder",
 *     "access" = "Drupal\locationentity\Entity\Access\LocationAccessControlHandler",
 *     "views_data" = "Drupal\locationentity\Entity\Views\LocationViewsData",
 *     "form" = {
 *       "default" = "Drupal\locationentity\Form\LocationForm",
 *       "add" = "Drupal\locationentity\Form\LocationForm",
 *       "edit" = "Drupal\locationentity\Form\LocationForm",
 *       "delete" = "Drupal\locationentity\Form\LocationDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\locationentity\Entity\Routing\LocationHtmlRouteProvider",
 *     },
 *     "translation" = "Drupal\locationentity\Entity\Translation\LocationTranslationHandler",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *     "status" = "status",
 *     "uid" = "user_id",
 *   },
 *   links = {
 *     "add-form" = "/locationentity/add/{locationentity_type}",
 *     "add-page" = "/locationentity/add",
 *     "canonical" = "/locationentity/{locationentity}",
 *     "collection" = "/admin/content/locationentity",
 *     "delete-form" = "/locationentity/{locationentity}/delete",
 *     "edit-form" = "/locationentity/{locationentity}/edit",
 *   },
 *   base_table = "locationentity_data",
 *   data_table = "locationentity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer locationentity",
 *   field_ui_base_route = "entity.locationentity_type.edit_form",
 * )
 */
class Location extends ContentEntityBase implements LocationInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function publish() {
    // Do not trigger any listeners if the location is already published.
    if (!$this->isPublished()) {
      $this->set('status', self::LOCATION_IS_PUBLISHED);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function unpublish() {
    // Do not trigger any listeners if the location is not actually published.
    if (!$this->isPublished()) {
      $this->set('status', self::LOCATION_IS_UNPUBLISHED);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);

    // Field defined in parent method for entity key 'id'.
    $fields['id']
      ->setDescription(new TranslatableMarkup('The ID of the location.'));

    // Field defined in parent method for entity key 'uuid'.
    $fields['uuid']
      ->setDescription(new TranslatableMarkup('The UUID of the location.'));

    // Field defined in parent method for entity key 'langcode'.
    $fields['langcode']
      ->setDescription(new TranslatableMarkup('The language code for the location.'));

    // Field defined in parent method for entity key 'bundle'.
    $fields['type']
      ->setDescription(new TranslatableMarkup('The type of the location.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Name'))
      ->setDescription(new TranslatableMarkup('The name of the location.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -50,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(new TranslatableMarkup('Authored by'))
      ->setDescription(new TranslatableMarkup('The username of the author of the location.'))
      ->setTranslatable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default:user')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDefaultValueCallback(static::class . '::getCurrentUserId');

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(new TranslatableMarkup('Publishing status'))
      ->setDescription(new TranslatableMarkup('A boolean indicating whether the location is published.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(new TranslatableMarkup('Created'))
      ->setDescription(new TranslatableMarkup('The time that the location was first created.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(new TranslatableMarkup('Last updated'))
      ->setDescription(new TranslatableMarkup('The time that the location was last updated.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
