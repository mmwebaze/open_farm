<?php

namespace Drupal\open_farm_analytics\Chart\Model;


class Data implements \JsonSerializable
{
    private $name;
    private $data = array();

    public function setName($name){
        $this->name = $name;
    }
    public function getName(){
        return $this->name;
    }
    public function setData($data = array()){
        $this->data = $data;
    }
    public function getData(){
        return $this->data;
    }
    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}