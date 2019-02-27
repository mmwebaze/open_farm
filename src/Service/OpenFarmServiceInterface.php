<?php

namespace Drupal\open_farm\Service;


interface OpenFarmServiceInterface
{
    public function getTaxonomyTerms($vid);
    public function getAnimalByTag($animalId);
    public function getAnimalByName($animalName);
    public function createNode( $data);
    public function getDataStores($status = 1);
}