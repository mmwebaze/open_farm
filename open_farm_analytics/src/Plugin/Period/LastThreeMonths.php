<?php

namespace Drupal\open_farm_analytics\Plugin\Period;

use Drupal\open_farm\Plugin\Period\AbstractPeriod;


/**
 * Define a concrete class for a period.
 *
 * @Period(
 *   id = "last_3_months",
 *   name = @Translation("Last Three Months")
 * )
 */
class LastThreeMonths extends AbstractPeriod
{
    public function period(){
        /*return array(
            ">=" => Date('Y-m-01', strtotime("-3 months")),
            "<=" => Date("Y-m-t", strtotime("last month"))
        );*/

        return array(
            'dateformat' => '%Y-%M',
            'params' => array(
            ':PARAM1' => Date("Y-F",strtotime("-3 Months")),
            ':PARAM2' =>Date("Y-F",strtotime("-2 Months")),
            ':PARAM3' =>Date("Y-F",strtotime("-1 Months"))
        ));
    }
}