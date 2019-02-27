<?php

namespace Drupal\open_farm\Service;

use Drupal\Core\Database\Driver\mysql\Connection;

class OpenFarmDataManager implements OpenFarmDataServiceInterface
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

    public function addDataValue($values)
    {
        if (isset($values)) {
            $value = array(
                'animal_tag' => $values['animal_tag'],
                'data_element' => $values['data_element'],
                'period' => $values['period'],
                'collection_date' => $values['collection_date'],
                'amount' => $values['amount'],
            );
            //print_r($value);die('value');
            try {
                $this->connection->insert('open_farm_data_value')
                    ->fields([
                        'animal_tag', 'data_element', 'period', 'collection_date', 'amount'
                    ])->values($value)->execute();
            } catch (\Exception $e) {
                print_r($e->getMessage());die($e->getMessage());
            }
        }
    }
    public function getAnimalData($animalId = '*'){
        $query = $this->connection->select('open_farm_data_value', 'dv');
        if ($animalId == '*'){
            $query->fields('dv');
        }
        else{
            $query->fields('dv')->condition('animal_tag', $animalId);
        }

        $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(20);
        $results = $pager->execute()->fetchAll();
        return $results;
    }
}