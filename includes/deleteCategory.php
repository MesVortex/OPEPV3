<?php

require_once ('/xampp/htdocs/brief8/OPEPV3/class/category.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/categoryDAO.php');


if(isset($_POST['category_id'])){
  $newCategory = new Category($_POST['category_id'],'placeholder');
  $CategoryObj = new CategoryDAO();
  $CategoryObj->deleteCategory($newCategory);
  header("Location: ../dashboard.php");
}