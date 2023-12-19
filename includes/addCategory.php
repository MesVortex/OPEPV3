<?php

require_once ('/xampp/htdocs/brief8/OPEPV3/class/category.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/categoryDAO.php');


if(isset($_POST['categoryName'])){
  $newCategory = new Category(1,$_POST['categoryName']);
  $CategoryObj = new CategoryDAO();
  $CategoryObj->addCategory($newCategory);
  header("Location: ../dashboard.php");
}