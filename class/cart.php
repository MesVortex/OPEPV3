<?php 

class Cart {
  private $userID;
  private $plants = array();

  public function __construct($userID, $plants){
    $this->userID = $userID;
    $this->plants = $plants;
  }

  public function getUserID(){
    return $this->userID;
  }

  public function getPlants(){
    return $this->plants;
  }

}