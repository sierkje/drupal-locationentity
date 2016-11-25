<?php

namespace Drupal\locationentity\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\locationentity\Entity\LocationType;

/**
 * Class LocationTypeForm.
 *
 * @package Drupal\locationentity\Form
 */
class LocationTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\locationentity\LocationTypeInterface $type */
    $type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $type->label(),
      '#description' => $this->t('Name of the location type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $type->id(),
      '#machine_name' => [
        'exists' => [LocationType::class, 'load'],
        'source' => ['label'],
      ],
      '#disabled' => !$type->isNew(),
      '#description' => t('A unique machine-readable name for this location type. It must only contain lowercase letters, numbers, and underscores. This name will be used for constructing the URL of the <em>%location-add</em> page, in which underscores will be converted into hyphens.', [
        '%location-add' => t('Add location'),
      ]),
    ];

    $form['description'] = [
      '#title' => t('Description'),
      '#type' => 'textarea',
      '#default_value' => $type->getDescription(),
      '#description' => $this->t('This text will be displayed on the <em>%location-add</em> page.', [
        '%location-add' => t('Add location'),
      ]),
      '#required' => TRUE,
    ];

    if (!$type->isNew()) {
      $form['uuid'] = [
        '#type' => 'textfield',
        '#title' => $this->t('UUID'),
        '#maxlength' => 255,
        '#default_value' => $type->uuid(),
        '#description' => $this->t('UUID of the location type.'),
        '#disabled' => TRUE,
      ];
    }

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
