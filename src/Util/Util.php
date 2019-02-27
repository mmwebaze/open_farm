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
                'collection_date' => $result->collection_date,
                'amount' => $result->amount,
                //'time_queued' => Util::getDate($result->timestamp_when_queued),
                //'time_sent' => Util::getDate($result->timestamp_when_sent),
            ];
        }
        return $output;
    }
}