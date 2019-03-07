<?php

namespace Drupal\open_farm\Plugin\Period;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Period plugins.
 */
interface PeriodInterface extends PluginInspectionInterface
{
    public function period();
    /**
     * Return the machine name of the period.
     *
     * @return string
     *   Returns the name as a string.
     */
    public function getPeriodName();
}