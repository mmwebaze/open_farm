<?php

namespace Drupal\open_farm_analytics\Plugin\Period;

use Drupal\open_farm\Plugin\Period\AbstractPeriod;

/**
 * Define a concrete class for a period.
 *
 * @Period(
 *   id = "this_month",
 *   name = @Translation("This Month")
 * )
 */
class ThisMonth extends AbstractPeriod
{
    public function period(){
        //SELECT * FROM open_farm_data_value WHERE date_format(date_collected, '%Y-%M') = '2019-March'
        return array(
            'dateformat' => '%Y-%M',
            'params' => array(
                ':PARAM3' =>Date("Y-F",strtotime("0 Months"))
            )
        );
    }
}