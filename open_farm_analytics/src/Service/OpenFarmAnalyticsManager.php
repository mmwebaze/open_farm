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

    public function startExport(){

    }
}