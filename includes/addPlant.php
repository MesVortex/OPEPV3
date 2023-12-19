<?php 

require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/plantDAO.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/plant.php');

if(isset($_POST['plant_name']) && !empty($_FILES['plant_img']) && isset($_POST['plant_price']) && isset($_POST['category_id'])){
  $newPlant = new Plant(1,$_POST['plant_name'], $_POST['category_id'], $_FILES['plant_img']['name'], $_POST['plant_price'], $_FILES['plant_img']['tmp_name']);
  $plantObj = new PlantDAO();
  $plantObj->addPlant($newPlant);
  header("Location: ../dashboard.php");
}