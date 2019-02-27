<?php

namespace Drupal\open_farm\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm\Service\OpenFarmServiceInterface;

/**
 * Class OpenFarmController.
 */
class OpenFarmController extends ControllerBase {

  /**
   * Drupal\open_farm\Service\OpenFarmServiceInterface definition.
   *
   * @var \Drupal\open_farm\Service\OpenFarmServiceInterface
   */
  protected $openFarmManagerService;

  /**
   * Constructs a new OpenFarmController object.
   */
  public function __construct(OpenFarmServiceInterface $open_farm_manager_service) {
    $this->openFarmManagerService = $open_farm_manager_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('open_farm.manager_service')
    );
  }

  /**
   * Milkcollection.
   *
   * @return string
   *   Return Hello string.
   */
  public function milkCollection() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: milkCollection')
    ];
  }
  /**
   * Eggcollection.
   *
   * @return string
   *   Return Hello string.
   */
  public function eggCollection() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: eggCollection')
    ];
  }

}
