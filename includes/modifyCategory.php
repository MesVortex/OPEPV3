<?php

require_once ('/xampp/htdocs/brief8/OPEPV3/class/category.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/categoryDAO.php');


if(isset($_POST['updatedCategoryID']) && isset($_POST['newCategoryName'])){
  $newCategory = new Category($_POST['updatedCategoryID'],$_POST['newCategoryName']);
  $CategoryObj = new CategoryDAO();
  $CategoryObj->updateCategoryName($newCategory);
  header("Location: ../dashboard.php");
}