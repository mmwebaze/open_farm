<?php

namespace Drupal\open_farm\Entity;

use Drupal\Console\Bootstrap\Drupal;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Animal entity.
 *
 * @ingroup open_farm
 *
 * @ContentEntityType(
 *   id = "animal",
 *   label = @Translation("Animal"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\open_farm\AnimalListBuilder",
 *     "views_data" = "Drupal\open_farm\Entity\AnimalViewsData",
 *     "translation" = "Drupal\open_farm\AnimalTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\open_farm\Form\AnimalForm",
 *       "add" = "Drupal\open_farm\Form\AnimalForm",
 *       "edit" = "Drupal\open_farm\Form\AnimalForm",
 *       "delete" = "Drupal\open_farm\Form\AnimalDeleteForm",
 *     },
 *     "access" = "Drupal\open_farm\AnimalAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\open_farm\AnimalHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "animal",
 *   data_table = "animal_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer animal entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/animal/{animal}",
 *     "add-form" = "/admin/structure/animal/add",
 *     "edit-form" = "/admin/structure/animal/{animal}/edit",
 *     "delete-form" = "/admin/structure/animal/{animal}/delete",
 *     "collection" = "/admin/structure/animal",
 *   },
 *   field_ui_base_route = "animal.settings"
 * )
 */
class Animal extends ContentEntityBase implements AnimalInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Created by'))
      ->setDescription(t('The user ID of author of the Animal entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['data_store'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Data store'))
        ->setDescription(t('The data store the animal belongs to.'))
        ->setSetting('target_type', 'open_farm_data_store')
        ->setSetting('handler', 'default')
        ->setDefaultValue([1])
        /*->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'author',
            'weight' => 0,
        ])*/
        ->setDisplayOptions('form', [
            'type' => 'entity_reference_autocomplete',
            'weight' => 5,
            'settings' => [
                'match_operator' => 'CONTAINS',
                'size' => '60',
                'autocomplete_type' => 'tags',
                'placeholder' => '',
            ],
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Animal entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

      $fields['tag_id'] = BaseFieldDefinition::create('string')
          ->setLabel(t('Tag ID'))
          ->setDescription((t('Unique animal identifier')))
          ->setSettings(array(
              'max_length' => 255,
              'text_processing' => 0,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'string_textfield',
              'weight' => -4,
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE)
          ->setRequired(TRUE);

      $openFarmService = \Drupal::service('open_farm.manager_service');
      $gender = $openFarmService->getTaxonomyTerms('gender');

      $fields['animal_gender'] = BaseFieldDefinition::create('list_integer')
          ->setLabel(t('Gender'))
          ->setDescription(t('The gender of the animal.'))
          ->setSettings([
              'allowed_values' => $gender
          ])
          ->setDefaultValue(array_search('female', $gender))
          /*->setDisplayOptions('view', [
              'label' => 'above',
              'type' => 'string',
              'weight' => -4,
          ])*/
          ->setDisplayOptions('form', [
              'type' => 'options_select',
              'weight' => -4,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE)
          ->setRequired(TRUE);

      $animalType = $openFarmService->getTaxonomyTerms('animal_type');
      $fields['animal_type'] = BaseFieldDefinition::create('list_integer')
          ->setLabel(t('Type'))
          ->setDescription(t('The type of the animal.'))
          ->setSettings([
              'allowed_values' => $animalType
          ])
          ->setDefaultValue(array_search('Cow', $animalType))
          /*->setDisplayOptions('view', [
              'label' => 'above',
              'type' => 'string',
              'weight' => -4,
          ])*/
          ->setDisplayOptions('form', [
              'type' => 'options_select',
              'weight' => -4,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE)
          ->setRequired(TRUE);

      $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Stock status'))
      ->setDescription(t('A boolean indicating whether the Animal is available.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

      $fields['active'] = BaseFieldDefinition::create('boolean')
          ->setLabel(t('Productivity status'))
          ->setDescription(t('A boolean indicating whether the Animal produces milk, eggs e.t.c.'))
          ->setDefaultValue(TRUE)
          ->setDisplayOptions('form', [
              'type' => 'boolean_checkbox',
              'weight' => -2,
          ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
  public function setTagId($tagId){
      $this->set('tag_id', $tagId);
      return $this;
  }
  public function getTagId(){
      return $this->get('tag_id')->value;
  }
  public function setGender($gender){
      $this->set('animal_gender', $gender);
      return $this;
  }
  public function getGender(){
      return $this->get('animal_gender')->value;
  }
  public function setAnimalType($animalType){
      $this->set('animal_type', $animalType);
      return $this;
  }
  public function getAnimalType(){
      return $this->get('animal_type')->value;
  }
  public function setDataStore($dataStore){
      $this->set('data_store', $dataStore);
      return $this;
  }
  public function getDataStore(){
      return $this->get('data_store')->entity;
  }
    public function isActive() {
        return (bool) $this->getEntityKey('active');
    }

    /**
     * {@inheritdoc}
     */
    public function setActive($active) {
        $this->set('active', $active ? TRUE : FALSE);
        return $this;
    }
}
