<?php

namespace Drupal\open_farm\Service;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use \Drupal\node\Entity\Node;
use \Drupal\Core\Entity\EntityStorageException;


class OpenFarmServiceManager implements OpenFarmServiceInterface
{
    protected $entityTypeManager;
    public function __construct(EntityTypeManager $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function getTaxonomyTerms($vid, $termName = NULL)
    {
        $taxonomyTerms = array();
        try{
            $storage = $this->entityTypeManager->getStorage('taxonomy_term');
            $query = $storage->getQuery()->condition('vid', $vid);

            if(isset($termName)){
                $query = $storage->getQuery()->condition('name', $termName);
            }
            $tids = $query->execute();
            $terms = $storage->loadMultiple($tids);

            foreach ($terms as $term){
                $taxonomyTerms[$term->id()] = $term->getName();
            }
            return $taxonomyTerms;
        }
        catch(PluginNotFoundException $pluginNotFoundException){
            print_r($pluginNotFoundException->getMessage());
        }
        catch (InvalidPluginDefinitionException $invalidPluginDefinitionException){
            print_r($invalidPluginDefinitionException->getMessage());
        }

    }
    public function getAnimalByTag($animalTag){
        try{
            $storage = $this->entityTypeManager->getStorage('animal');
            $animalIds = $storage->getQuery()->condition('tag_id', $animalTag)->execute();
            $animal = $storage->loadMultiple($animalIds);

            return $animal;
        }
        catch (PluginNotFoundException $pluginNotFoundException){
            print_r($pluginNotFoundException->getMessage());
        }
        catch (InvalidPluginDefinitionException $invalidPluginDefinitionException){
            print_r($invalidPluginDefinitionException->getMessage());
        }
        return 0;
    }
    public function getAnimals(array $options = [])
    {
        try{
            $storage = $this->entityTypeManager->getStorage('animal');
            $query = $storage->getQuery();

            foreach ($options as $field => $option){
                $query->condition($field, $option);
            }
            $animalIds = $query->execute();
            $animals = $storage->loadMultiple($animalIds);

            return $animals;
        }
        catch (PluginNotFoundException $pluginNotFoundException){
            print_r($pluginNotFoundException->getMessage());
        }
        catch (InvalidPluginDefinitionException $invalidPluginDefinitionException){
            print_r($invalidPluginDefinitionException->getMessage());
        }
        return 0;
    }

    public function getAnimalByName($animalName){

    }
    public function createNode($data){

        $node = Node::create($data);
        try{
            $node->save();
        }
        catch (EntityStorageException $entityStorageException){
            print_r($entityStorageException->getMessage());
        }
    }
    public function getDataStores($status = 1){
        try{
            $storage = $this->entityTypeManager->getStorage('open_farm_data_store');
            $storeIds = $storage->getQuery()->condition('status', $status)->execute();
            $stores = $storage->loadMultiple($storeIds);

            return $stores;
        }
        catch (PluginNotFoundException $pluginNotFoundException){
            print_r($pluginNotFoundException->getMessage());
        }
        catch (InvalidPluginDefinitionException $invalidPluginDefinitionException){
            print_r($invalidPluginDefinitionException->getMessage());
        }
    }
}