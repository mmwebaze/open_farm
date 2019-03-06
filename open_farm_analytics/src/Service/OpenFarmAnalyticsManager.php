<?php

namespace Drupal\open_farm_analytics\Service;

use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\open_farm\Service\OpenFarmServiceInterface;

class OpenFarmAnalyticsManager implements OpenFarmAnalyticsInterface
{
    protected $openFarmService;
    /**
     * Drupal\Core\Database\Driver\mysql\Connection definition.
     *
     * @var \Drupal\Core\Database\Driver\mysql\Connection
     */
    protected $connection;
    public function __construct(OpenFarmServiceInterface $openFarmService, Connection $connection)
    {
        $this->openFarmService = $openFarmService;
        $this->connection = $connection;
    }
    public function getDataValues($dataElement, $periods, $animalTags){

        $query = $this->connection->select('open_farm_data_value', 'dv');
        $query->fields('dv', ['animal_tag', 'collection_date', "data_element"])->condition('data_element', $dataElement);
        //$query->addField('dv', 'amount');
        $query->addExpression('SUM(dv.amount)', 'Amount');
        //$orGroup = $query->orConditionGroup();
        $groupPeriods = $this->createOrCondition($query, $periods, 'collection_date');
        $groupAnimalTags = $this->createOrCondition($query, $animalTags, 'animal_tag');
        $query->condition($groupPeriods);
        $query->condition($groupAnimalTags);
        $query->groupBy("animal_tag");
        $query->groupBy("collection_date");
        $query->groupBy("data_element");
        //$query->groupBy("data_element");
        //$query->groupBy("collection_date");

        $results = $query->execute()->fetchAll();

        return $results;
    }

    /**
     * @param \Drupal\Core\Database\Query\Select $query
     * @param $conditions
     */
    private function createOrCondition(&$query, $conditions, $field){
        $group = $query->orConditionGroup();
        foreach ($conditions as $value){
            $group->condition($field, $value);
        }
        return $group;
    }
}