<?php 

require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/plantDAO.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/plant.php');

if(isset($_POST['plant_id'])){
  $newPlant = new Plant($_POST['plant_id'] , 'placeholder', 'placeholder', 'placeholder', 'placeholder', 'placeholder');
  $plantObj = new PlantDAO();
  $plantObj->deletePlant($newPlant);
  header("Location: ../dashboard.php");
}