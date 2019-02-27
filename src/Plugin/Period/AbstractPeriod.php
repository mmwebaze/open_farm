<?php

namespace Drupal\open_farm\Plugin\Period;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class Period plugins.
 */
abstract class AbstractPeriod extends PluginBase implements PeriodInterface, ContainerFactoryPluginInterface
{
    use StringTranslationTrait;
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration, $plugin_id, $plugin_definition
        );
    }
    /**
     * Get Period Name.
     *
     * @return string
     *   period Name.
     */
    public function getPeriodName() {
        return $this->pluginDefinition['name'];
    }
}