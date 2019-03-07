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
        $query->fields('dv', ['animal_tag'])->condition('data_element', $dataElement);
        //$query->addExpression('MONTHNAME(date_collected)', 'category');
        $query->addExpression("date_format(date_collected, '".$periods['dateformat']."')", 'category');
        $query->addExpression('SUM(dv.amount)', 'Amount');
        //$monthGrp = $this->createOrWhere($query, $periods);
        //$query->condition($monthGrp);
        //$orGroup = $query->orConditionGroup();
        //$groupPeriods = $this->createOrCondition($query, $periods, 'date_collected');
        //$this->createDateRange($query, $periods, 'date_collected');
        //$query->condition('date_collected', "MONTHNAME(\'2019-02-25\')");
        //print_r(implode(" OR ", array_keys($periods)));die();

        $query->where("date_format(date_collected, '".$periods['dateformat']."') =".implode(" OR ", array_keys($periods['params'])), $periods['params']);
        //$query->where("date_format(date_collected,'%Y-%M') =".":PARAM1 OR :PARAM2", array(':PARAM1' => '2019-February', ':PARAM2' => '2019-January'));
        $groupAnimalTags = $this->createOrCondition($query, $animalTags, 'animal_tag');
        //$query->condition($groupPeriods);
        $query->condition($groupAnimalTags);

        $query->groupBy("animal_tag");
        $query->groupBy("category");
        //$query->groupBy("date_collected");
        $query->groupBy("data_element");

        //$query->addTag('debug');

        $results = $query->execute()->fetchAll();
        //$results = $query->execute()->getQueryString();
        return $results;
    }

    /**
     * @param \Drupal\Core\Database\Query\Select $query
     * @param $conditions
     */
    private function createOrCondition(&$query, $conditions, $field){
        $group = $query->orConditionGroup();
        foreach ($conditions as  $value){
            $group->condition($field, $value);
        }
        return $group;
    }
    private function createDateRange(&$query, $conditions, $field){
        foreach ($conditions as $limit => $value){
            $query->condition($field, $value, $limit);
        }
    }
    /**
     * @param \Drupal\Core\Database\Query\Select $query
     * @param $conditions
     */
    private function createOrWhere(&$query, $conditions){
        $group = $query->orConditionGroup();
        foreach ($conditions as  $value){
            //print_r($value);
            $group->where("date_format(date_collected, '%Y-%M') = :PARAM1", array(':PARAM1' => $value));
        }
        return $group;
    }
}