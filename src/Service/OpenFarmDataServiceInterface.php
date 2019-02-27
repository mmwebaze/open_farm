<?php

namespace Drupal\open_farm\Service;


interface OpenFarmDataServiceInterface
{
    public function addDataValue($values);
    public function getAnimalData($animalId = '*');
}