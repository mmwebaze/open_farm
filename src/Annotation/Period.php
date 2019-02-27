<?php

namespace Drupal\open_farm\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an Period annotation object.
 *
 * @Annotation
 */
class Period extends Plugin
{
    /**
     * The plugin ID.
     *
     * @var string
     */
    public $id;

    /**
     * The plugin name.
     *
     * @var string
     */
    public $name;
}