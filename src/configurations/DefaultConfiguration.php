<?php
namespace Drupal\open_farm\configurations;

class DefaultConfiguration
{
    public static function getPeriods(){
        return array(
            'morning',
            'afternoon'
        );
    }
    public static function getGender(){
        return array(
            'male',
            'female'
        );
    }
    public static function getAnimalType(){
        return array(
            'Cow','Sheep','Goat'
        );
    }
}