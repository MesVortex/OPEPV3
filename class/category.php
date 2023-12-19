<?php 

class Category{
  private $ID;
  private $name;

  public function __construct($ID, $name){
    $this->ID = $ID;
    $this->name = $name;
  }

  // GETTERS

  public function getID(){
    return $this->ID;
  }

  public function getName(){
    return $this->name;
  }
}