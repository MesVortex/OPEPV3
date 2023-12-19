<?php 
require_once ('../class/DAOs/cartDAO.php');

if(isset($_POST['clear'])){
  $clearObj = new cartDAO();
  $clearObj->deleteAllPlantsInCart();
  header('Location: ../home.php');
}