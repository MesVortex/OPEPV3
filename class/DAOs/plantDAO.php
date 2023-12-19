<?php

require_once "./class/dbConnect.php";
require_once "./class/plant.php";

class PlantDAO {
  private $DbConnect;

  public function __construct(){
    $this->DbConnect = Database::connectionCheck()->connect();
  }

  public function getAllPlants() {
    $query = "SELECT plants.*, category.category_name 
              FROM plants 
              JOIN category 
              ON plants.category_id = category.category_id";
    $result = $this->DbConnect->query($query);
    $DbPlants = $result->fetchAll(PDO::FETCH_ASSOC);
    $plants = array();
    foreach($DbPlants as $P){
      $plants[] = new Plant($P['plant_id'], $P['plant_name'], $P['category_id'], $P['plant_img'], $P['plant_price'], $P['quantity']);
    }
    return $plants;
  }

  public function getPlantsByCategory(int $categoryID){
    $query = "SELECT * FROM plants WHERE category_id = :categoryID";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":categoryID", $categoryID);
    $stmt->execute();
    $DbPlants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $plants = array();
    foreach($DbPlants as $P){
      $plants[] = new Plant($P['plant_id'], $P['plant_name'], $P['category_id'], $P['plant_img'], $P['plant_price'], $P['quantity']);
    }
    return $plants;
  }

  public function getPlantsByName(String $PlantName){
    $query = "SELECT * FROM plants WHERE plant_name LIKE :PlantName";
    $stmt = $this->DbConnect->prepare($query);   
    $stmt->bindParam(":PlantName", $PlantName);
    $stmt->execute();
    $DbPlants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $plants = array();
    foreach($DbPlants as $P){
      $plants[] = new Plant($P['plant_id'], $P['plant_name'], $P['category_id'], $P['plant_img'], $P['plant_price'], $P['quantity']);
    }
    return $plants;
  }
}