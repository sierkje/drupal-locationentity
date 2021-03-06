<?php

namespace Drupal\locationentity\Entity\Views;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Location entities.
 */
class LocationViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['locationentity']['table']['base'] = [
      'field' => 'id',
      'title' => $this->t('Location'),
      'help' => $this->t('The Location ID.'),
    ];

    return $data;
  }

}
