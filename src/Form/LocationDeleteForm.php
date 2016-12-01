<?php

namespace Drupal\locationentity\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;

/**
 * Provides a form for deleting locations.
 *
 * @ingroup locationentity
 */
class LocationDeleteForm extends ContentEntityDeleteForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\locationentity\LocationInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  protected function getDeletionMessage() {
    if (!$this->entity->isDefaultTranslation()) {
      return $this->t('@language translation of location <em>%label</em> (ID: @id) has been deleted.', [
        '@language' => $this->entity->language()->getName(),
        '%label' => $this->entity->label(),
        '@id' => $this->entity->id(),
      ]);
    }

    return $this->t('Location <em>%label</em> (ID: @id) has been deleted.', [
      '%title' => $this->entity->label(),
      '@id' => $this->entity->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function logDeletionMessage() {
    $this->logger('locationentity')
      ->notice('Deleted location <em>%label</em> (ID: @id).', [
        '%label' => $this->entity->label(),
        '@id' => $this->entity->id(),
      ]);
  }

}
