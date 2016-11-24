<?php

namespace Drupal\locationentity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class LocationTypeForm.
 *
 * @package Drupal\locationentity\Form
 */
class LocationTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $locationentity_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $locationentity_type->label(),
      '#description' => $this->t("Label for the Location type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $locationentity_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\locationentity\Entity\LocationType::load',
      ),
      '#disabled' => !$locationentity_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $locationentity_type = $this->entity;
    $status = $locationentity_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Location type.', [
          '%label' => $locationentity_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Location type.', [
          '%label' => $locationentity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($locationentity_type->urlInfo('collection'));
  }

}
