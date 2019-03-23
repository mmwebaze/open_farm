<?php

namespace Drupal\open_farm\Util;


class Util
{
    public static function processResult($results){
        $output = array();
        foreach ($results as $result) {
            $output[$result->id] = [
                'animal_tag' => $result->animal_tag,
                'data_element' => $result->data_element,
                'collection_date' => $result->date_collected,
                'amount' => $result->amount,
            ];
        }
        return $output;
    }
}