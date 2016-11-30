<?php

namespace Drupal\locationentity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\locationentity\LocationMessengerTrait;

/**
 * Form controller for Location edit forms.
 *
 * @ingroup locationentity
 */
class LocationForm extends ContentEntityForm {

  use LocationMessengerTrait;

  /**
   * The location being used by this form.
   *
   * @var \Drupal\locationentity\LocationInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);

    $this->addStatusMessage($this->t('Saved location <em>%label</em>.', [
      '%label' => $this->entity->label(),
    ]));

    $form_state->setRedirect('entity.locationentity.canonical', [
      'locationentity' => $this->entity->id(),
    ]);

    return $status;
  }

}
