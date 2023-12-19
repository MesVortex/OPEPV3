<?php 
require_once ('../class/DAOs/cartDAO.php');

if(isset($_POST['order'])){
  $orderObj = new cartDAO();
  $orderObj->order();
  header('Location: ../home.php');
}