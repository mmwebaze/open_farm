<?php

namespace Drupal\open_farm\Service;


interface OpenFarmServiceInterface
{
    /**
     * Returns an array containing all taxonomy terms with specified vid. If a term name is specified, term with vid
     * and termName will be returned.
     *
     * @param string $vid
     *
     * @param string $termName
     *
     *
     * @return array
     * An array of taxonomy terms belong to vocabulary with specified vid.
     */
    public function getTaxonomyTerms($vid, $termName = null);
    public function getAnimalByTag($animalId);

    /**
     * Returns an array containing all animals;
     *
     * @param array $options
     * An associative array of options to control which options are queried. Leave empty to get all Animals.
     * The following keys are acceptable:
     * array(
     *  'name' => 'Entity name',
     *  'animal_gender'=> 'M',
     *  'data_store' => 'data store id the animal belongs to',
     *  'animal_type' => 'term id Animal Types taxonomy',
     *  'status' => '0 or 1', (see A boolean indicating whether the Animal is still in stock.)
     *  'active' => '0 or 1', (see A boolean indicating whether the Animal produces milk, eggs e.t.c.)
     * )
     *
     * @return array
     * An array of entities of type Animal
     */
    public function getAnimals(array $options = []);
    public function getAnimalByName($animalName);
    public function createNode( $data);
    public function getDataStores($status = 1);
}