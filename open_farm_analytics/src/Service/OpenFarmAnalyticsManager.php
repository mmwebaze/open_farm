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
        $query->addExpression("date_format(date_collected, '".$periods['dateformat']."')", 'category');
        $query->addExpression('SUM(dv.amount)', 'Amount');
        $query->where("date_format(date_collected, '".$periods['dateformat']."') =".implode(" OR ", array_keys($periods['params'])), $periods['params']);
        //$query->where("date_format(date_collected,'%Y-%M') =".":PARAM1 OR :PARAM2", array(':PARAM1' => '2019-February', ':PARAM2' => '2019-January'));
        $groupAnimalTags = $this->createOrCondition($query, $animalTags, 'animal_tag');
        $query->condition($groupAnimalTags);

        $query->groupBy("animal_tag");
        $query->groupBy("category");
        $query->groupBy("data_element");

        $results = $query->execute()->fetchAll();
        //$results = $query->execute()->getQueryString();
        return $results;
    }

    /**
     * @inheritdoc
     */
    public function saveChartConfig(array $chartConfig){
        try {
            $this->connection->insert('open_farm_analytics_stored_charts')
                ->fields([
                    'uuid', 'chart_title', 'chart_period', 'data_element', 'animal_tags'
                ])->values(['uuid'=>$chartConfig->uuid, 'chart_title'=>$chartConfig, 'chart_period'=>$chartConfig->chart_period,
                    'data_element'=>$chartConfig->data_element, 'animal_tags'=>$chartConfig->animal_tags])->execute();
        } catch (\Exception $e) {
            //@todo replace by logging and redirect to error page
            print_r($e->getMessage());
        }
    }
    /**
     * @inheritdoc
     */
    public function getChartConfigs(array $options, $limit = 6){
        $query = $this->connection->select('open_farm_analytics_stored_charts', 'ch');
        $query->fields('ch');
        if(!empty($options)){
            $query->condition('uuid', $options['uuid']);
        }
        $query->range(0, $limit);
        return $query->execute()->fetchAll();
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