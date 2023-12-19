<?php

require_once "./class/dbConnect.php";
require_once "./class/category.php";


class CategoryDAO {
  private $DbConnect;

  public function __construct(){
    $this->DbConnect = Database::connectionCheck()->connect();
  }

  public function getAllCategories(){
    $query = "SELECT * FROM category";
    $result = $this->DbConnect->query($query);
    $DbCategories = $result->fetchAll(PDO::FETCH_ASSOC);
    $categories = array();
    foreach($DbCategories as $C){
      $categories[] = new Category($C['category_id'], $C['category_name']);
    }
    return $categories;
  }
}