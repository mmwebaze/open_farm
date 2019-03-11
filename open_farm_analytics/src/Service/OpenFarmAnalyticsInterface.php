<?php

namespace Drupal\open_farm_analytics\Service;


interface OpenFarmAnalyticsInterface
{
    public function getDataValues($dataElement, $periods, $animalTags);

    /**
     * Stores stored chart configurations.
     *
     * @param array $chartConfig
     * A array containing key value pairs of chart configs.
     * uuid => 'universally unique identifier for a specific chart config'
     * chart_title => 'The title associated with the chart'
     * chart_period => 'The period associated with the chart'
     * data_element => 'The data element (milk, eggs) associated with this chart configuration.'
     * animal_tags => 'Animals identifiers associated with this chart configuration'
     *
     * @return integer
     * A 0 will be returned if not successful otherwise a 1
     */
    public function saveChartConfig(array $chartConfig);

    /**
     * Retrieves stored chart configurations.
     *
     * @param array $options
     * A array containing key value pairs. Keep array empty to retrieve configs without specifying a uuid.
     * uuid => 'universally unique identifier for a specific chart config'
     *
     * @param integer $limit
     * The number of chart configs that can be retrieved.
     *
     * @return array
     * Returns an array of chart configurations.
     */
    public function getChartConfigs(array $options, $limit);
}