<?php

require_once ('/xampp/htdocs/brief8/OPEPV3/class/category.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/dbConnect.php');



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

  public function addCategory($newCategory) {
    $newCategoryName = $newCategory->getName();
    $query = "INSERT INTO category(category_name) VALUES(:categoryName)";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":categoryName", $newCategoryName);
    $stmt->execute();
  }

  public function updateCategoryName($Category) {
    $categoryID = $Category->getID();
    $categoryName = $Category->getName();
    $query = "UPDATE category SET category_name = :CategoryName WHERE category_id = :CategoryID";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":CategoryName", $categoryName);
    $stmt->bindParam(":CategoryID", $categoryID);
    $stmt->execute();
  }

  public function deleteCategory($Category) {
    $categoryID = $Category->getID();
    $query = "DELETE FROM category WHERE category_id = :categoryID";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":categoryID", $categoryID);
    $stmt->execute();
  }
  
}