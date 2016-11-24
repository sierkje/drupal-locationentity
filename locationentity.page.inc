<?php

/**
 * @file
 * Contains locationentity.page.inc.
 *
 * Page callback for Location entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Location templates.
 *
 * Default template: locationentity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_locationentity(array &$variables) {
  // Fetch Location Entity Object.
  $locationentity = $variables['elements']['#locationentity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

/**
* Prepares variables for a custom entity type creation list templates.
*
* Default template: locationentity-content-add-list.html.twig.
*
* @param array $variables
*   An associative array containing:
*   - content: An array of locationentity-types.
*
* @see block_content_add_page()
*/
function template_preprocess_locationentity_content_add_list(&$variables) {
  $variables['types'] = [];
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = [
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.locationentity.add_form', [
        'locationentity_type' => $type->id()
      ], ['query' => $query])),
      'description' => [
      '#markup' => $type->label(),
      ],
      'title' => $type->label(),
      'localized_options' => [
      'query' => $query,
      ],
    ];
  }
}
