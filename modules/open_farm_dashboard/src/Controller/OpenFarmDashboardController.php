<?php

namespace Drupal\open_farm_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

class OpenFarmDashboardController extends ControllerBase
{
    public function dashboard() {
        $render = array(
            '#theme' => 'open_farm_dashboard',
            '#viz_data' => ['container1', 'container2', 'container3'],
            '#attached' => array(
                'library' => array('open_farm_dashboard/dashboard'),
                'drupalSettings' => array(
                    'dashlets' => ['container1', 'container2', 'container3']
                )
            )
        );

        return $render;
    }
}