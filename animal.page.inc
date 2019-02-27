<?php

/**
 * @file
 * Contains animal.page.inc.
 *
 * Page callback for Animal entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Animal templates.
 *
 * Default template: animal.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_animal(array &$variables) {
  // Fetch Animal Entity Object.
  $animal = $variables['elements']['#animal'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
