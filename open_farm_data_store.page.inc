<?php

/**
 * @file
 * Contains open_farm_data_store.page.inc.
 *
 * Page callback for Open farm data store entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Open farm data store templates.
 *
 * Default template: open_farm_data_store.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_open_farm_data_store(array &$variables) {
  // Fetch OpenFarmDataStore Entity Object.
  $open_farm_data_store = $variables['elements']['#open_farm_data_store'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
