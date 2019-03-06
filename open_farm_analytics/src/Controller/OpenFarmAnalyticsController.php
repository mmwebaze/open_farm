<?php

namespace Drupal\open_farm_analytics\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\open_farm_analytics\Chart\Model\Data;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class OpenFarmAnalyticsController.
 */
class OpenFarmAnalyticsController extends ControllerBase {

  /**
   * Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface definition.
   *
   * @var \Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface
   */
  protected $openFarmAnalyticsManagerService;

  /**
   * Constructs a new OpenFarmAnalyticsController object.
   */
  public function __construct(OpenFarmAnalyticsInterface $openFarmAnalyticsManagerService) {
    $this->openFarmAnalyticsManagerService = $openFarmAnalyticsManagerService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('open_farm_analytics.manager_service')
    );
  }

  /**
   * Data Value.
   *
   * @return string
   *   Return Hello string.
   */
  public function getDataValue(Request $request) {
      $response = ['none'];

     /* if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
          $response = [];
          $dataElement = $request->query->get('de');
          $period = $request->query->get('pe');
          $animalTags = $request->query->get('tags');
          //$response['de'] = $dataElement.' ***';
          //$response['pe'] = $period;
          //$response['tags'] = $animalTags;

          $values['de'] =  'Milk';
          $periods = ["2019-02-27", "2019-02-25"];
          $animalTags = ["1234", "12345"];
          $response['categories'] = ["2019-02-25", "2019-02-27"];
          $response['datavalues'] = $this->openFarmAnalyticsManagerService->getDataValues('Milk', $periods, $animalTags);
          return new JsonResponse( $response );
      }*/
      $values['de'] =  'Milk';
      $periods = ["2019-02-25", "2019-02-27"];
      $animalTags = ["1234", "12345"];

      foreach ($animalTags as $animalTag){
          $data[$animalTag] = [$animalTag];
      }

      $categories = ["2019-02-25", "2019-02-27"];
      $response = $this->openFarmAnalyticsManagerService->getDataValues('Milk', $periods, $animalTags);


      $series = [];
      foreach ($animalTags as $k => $animalTag){
          $chartData = new Data();
          $chartData->setName($animalTag);

          foreach ($categories as $key => $category){
              $count = 0;
              foreach ($response as  $value ){
                  if ($animalTag == $value->animal_tag){
                      if($category == $value->collection_date){
                          $data = $chartData->getData();
                          array_push($data, (int) $value->Amount);
                          $chartData->setData($data);
                          $count++;
                      }
                  }

              }
              if($count == 0){
                  $data = $chartData->getData();
                  array_push($data, 0);
                  $chartData->setData($data);
              }
          }

          array_push($series, $chartData);
      }
      $chart = new \stdClass();
      $chart->series = $series;
      return new JsonResponse( $chart);
  }

}
