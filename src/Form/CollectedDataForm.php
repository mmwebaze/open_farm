<?php

namespace Drupal\open_farm\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\open_farm\Util\Util;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_farm\Service\OpenFarmDataServiceInterface;
use Drupal\Core\Path\CurrentPathStack;


class CollectedDataForm extends FormBase
{
    protected $openFarmDataService;
    protected $currentPath;

    public function __construct(OpenFarmDataServiceInterface $openFarmDataService, CurrentPathStack $currentPath){
        $this->openFarmDataService = $openFarmDataService;
        $this->currentPath = $currentPath;
    }
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('open_farm.data_manager'),
            $container->get('path.current')
        );
    }
    public function getFormId(){
        return 'open_farm_collected_data_form';
    }
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['data_search'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
        );
        $form['data_search']['animal_tag'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Animal Tag'),
            '#default_value' => '*',
            '#description' => $this->t('Enter an animal tag'),
            '#required' => TRUE,
        );
        $form['data_search']['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Search',
        );
        $header = array(
            "animal_tag" => $this->t('Animal Tag'), "data_element" => $this->t('Type'),
            "collection_date" => $this->t("Date Collected"), "amount" => $this->t('Amount Collected')
        );
        $csvExport = FALSE;
        $form['data_search_results'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t(''),
        );
        if (isset($_GET['animal_tag']) ){
            if ($_GET['animal_tag'] == '*'){
                $results = $this->openFarmDataService->getAnimalData();
                $output = Util::processResult($results);

                if (count($output) != 0){
                    $csvExport = TRUE;
                }
                $form['data_search_results']['table'] = [
                    '#type' => 'tableselect',
                    '#header' => $header,
                    '#options' => $output,
                    '#empty' => t('No data found.'),
                ];
            }
            else{
                $results = $this->openFarmDataService->getAnimalData($_GET['animal_tag']);
                $output = Util::processResult($results);
                if (count($output) != 0){
                    $csvExport = TRUE;
                }
                $form['data_search_results']['table'] = [
                    '#type' => 'tableselect',
                    '#header' => $header,
                    '#options' => $output,
                    '#empty' => t('No data found.'),
                ];
            }
        }

        if ($csvExport){
            $form['data_search_results']['submit_export_pdf'] = [
                '#type' => 'submit',
                '#value' => $this->t('Export to CSV'),
                '#submit' => array('::exportCSVHandler'),
            ];
        }

        $form['msg_results']['pager'] = array(
            '#type' => 'pager'
        );
        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state) {

        parent::validateForm($form, $form_state);
    }
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $args = array(
            'animal_tag' => $form_state->getValue('animal_tag')
        );
        $form_state->setRedirect('open_farm.open_farm_collected_data_form', $args);
    }
    public function exportCSVHandler(array &$form, FormStateInterface $form_state){
        die('start export');
    }
}