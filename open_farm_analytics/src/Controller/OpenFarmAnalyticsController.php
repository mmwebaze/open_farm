<?php

namespace Drupal\open_farm_analytics\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\open_farm\Plugin\Period\PeriodManager;
use Drupal\open_farm_analytics\Chart\Model\Data;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Plugin\Exception\PluginException;


/**
 * Class OpenFarmAnalyticsController.
 */
class OpenFarmAnalyticsController extends ControllerBase
{

    /**
     * Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface definition.
     *
     * @var \Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface
     */
    protected $openFarmAnalyticsManagerService;
    protected $periodManager;

    /**
     * Constructs a new OpenFarmAnalyticsController object.
     */
    public function __construct(OpenFarmAnalyticsInterface $openFarmAnalyticsManagerService, PeriodManager $periodManager)
    {
        $this->openFarmAnalyticsManagerService = $openFarmAnalyticsManagerService;
        $this->periodManager = $periodManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('open_farm_analytics.manager_service'),
            $container->get('plugin.manager.periods')
        );
    }

    /**
     * Data Value.
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *   Return  $response
     */
    public function getDataValue(Request $request)
    {
        $response = ['none'];

        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
             $dataElement = $request->query->get('de');
             $period = $request->query->get('pe');
             $animalTags = $request->query->get('tags');
             $chartTitle = $request->query->get('title');
             /*$test = new \stdClass();
             $test->period = $period;
             $test->tags = $animalTags;
             $test->title = $chartTitle;
             $test->dataElement = $dataElement;*/

             try{
                 $periodInstance = $this->periodManager->createInstance($period);
                 $periods = $periodInstance->period();
                 //return new JsonResponse($test);
             }
             catch (PluginException $e){
                 return new JsonResponse( $e->getMessage() );
             }

            foreach ($animalTags as $animalTag) {
                $data[$animalTag] = [$animalTag];
            }

            $dataValues = $this->openFarmAnalyticsManagerService->getDataValues($dataElement, $periods, $animalTags);

            $series = [];
            $periods = array_values($periods['params']);

            foreach ($animalTags as $k => $animalTag) {
                $chartData = new Data();
                $chartData->setName($animalTag);

                foreach ($periods as $period) {
                    $count = 0;
                    foreach ($dataValues as $value) {
                        if ($animalTag == $value->animal_tag) {
                            if ($period == $value->category) {
                                $data = $chartData->getData();
                                array_push($data, (int)$value->Amount);
                                $chartData->setData($data);
                                $count++;
                            }
                        }
                    }
                    if ($count == 0) {
                        $data = $chartData->getData();
                        array_push($data, 0);
                        $chartData->setData($data);
                    }
                }
                array_push($series, $chartData);
            }
            $chart = new \stdClass();
            $chart->series = $series;
            $chart->categories = $periods;
            $chart->title = $chartTitle;
            return new JsonResponse( $chart );
         }

        return new JsonResponse($response);
    }
}
