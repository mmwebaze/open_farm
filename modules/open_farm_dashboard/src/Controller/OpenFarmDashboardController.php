<?php

namespace Drupal\open_farm_dashboard\Controller;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm\Plugin\Period\PeriodManager;
use Drupal\open_farm_analytics\Chart\Model\Data;


class OpenFarmDashboardController extends ControllerBase
{
    /**
     * Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface definition.
     *
     * @var \Drupal\open_farm_analytics\Service\OpenFarmAnalyticsInterface
     */
    protected $openFarmAnalyticsManagerService;
    protected $periodManager;

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
    public function dashboard() {

        //This limit i.e number of chart configurations will be stored as part of the dashboard configurations.
        $storedVizs = $this->openFarmAnalyticsManagerService->getChartConfigs([], 5);
        $charts = array();
        foreach ($storedVizs as $storedViz)
        {
            $charts[$storedViz->uuid] = json_encode($this->createChartData($storedViz->data_element, $storedViz->chart_period, unserialize($storedViz->animal_tags), $storedViz->chart_title, $storedViz->chart_type));
        }
        $cols = 3; //@to_do this value will be stored as part of the dashboard configurations.
        $dashlets = count($charts);
        $rows = 1;
        if ($dashlets > $cols){
            $reminder = $dashlets % $cols;
            if ($reminder == 0){
                $rows = floor($dashlets/$cols);
            }
            else{
                $round = floor($dashlets/$cols);
                $rows = $rows + $round;
            }

        }

        $render = array(
            '#theme' => 'open_farm_dashboard',
            '#viz_data' => ['ids'=>array_keys($charts), 'rows' => $rows, 'cols' => $cols],
            '#attached' => array(
                'library' => array('open_farm_dashboard/dashboard'),
                'drupalSettings' => array(
                    'charts' => $charts
                )
            )
        );

        return $render;
    }
    private function createChartData($dataElement, $period, $animalTags, $chartTitle, $chartType){
        try{
            $periodInstance = $this->periodManager->createInstance($period);
            $periods = $periodInstance->period();
            //return new JsonResponse($test);
        }
        catch (PluginException $e){
            print_r( $e->getMessage() );
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
        $chart->chart = $chartType;

        return $chart;
    }
}