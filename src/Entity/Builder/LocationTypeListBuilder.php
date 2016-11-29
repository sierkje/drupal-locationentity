<?php

namespace Drupal\locationentity\Entity\Builder;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of location types.
 */
class LocationTypeListBuilder extends ConfigEntityListBuilder {

  const RESPONSIVE_PRIORITY_MEDIUM = 'priority-medium';

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    return [
      'name' => $this->t('Name'),
      'description' => [
        'data' => $this->t('Description'),
        'class' => [self::RESPONSIVE_PRIORITY_MEDIUM],
      ],
    ] + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\locationentity\LocationTypeInterface $entity */
    return [
      'name' => [
        'data' => $entity->label(),
        'class' => ['menu-label'],
      ],
      'description' => [
        'data' => [
          '#markup' => $entity->getDescription(),
        ],
      ],
    ] + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    // Place the edit operation after the operations added by field_ui.module
    // which have the weights 15, 20, 25.
    if (isset($operations['edit'])) {
      $operations['edit']['weight'] = 30;
    }

    return $operations;
  }

}
