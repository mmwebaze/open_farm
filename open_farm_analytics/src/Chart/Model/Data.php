<?php

namespace Drupal\open_farm_analytics\Chart\Model;


class Data implements \JsonSerializable
{
    private $type;
    private $columns = array();

    public function setChartType($type){
        $this->type = $type;
    }
    public function getChartType(){
        return $this->type;
    }
    public function addColumns($column = array()){
        array_push($this->columns, $column);
    }
    public function getColumns(){
        return $this->columns;
    }
    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}