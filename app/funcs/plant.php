<?php
require_once("./app/config/db.php");

function addP($data) {
  global $con;
  $file = $_FILES['plant_img']['name'];
  $folder = './assets/imgs/' . $file;
  $fileTmp = $_FILES['plant_img']['tmp_name'];
  $query = "INSERT INTO plants(plant_name, plant_img , plant_price, category_id) VALUES(?,?,?,?)";
  $stmt = $con->prepare($query);
  if ($stmt) {
      $stmt->bind_param("ssis", $data["plant_name"], $data["plant_img"] , $data["plant_price"], $data["category_id"]);
      if($stmt->execute()) {
        move_uploaded_file($fileTmp,$folder);
      }
      if ($stmt->affected_rows > 0) {
          return true;
      } else {
        return false;
      }
      $stmt->close();
  } else {
    return "Error preparing statement: " . $con->error;
  }
}


function getAllP() {
  global $con;
  $query = "SELECT plants.*, category.category_name FROM plants JOIN category ON plants.category_id = category.category_id";
  $stmt = $con->prepare($query);
  
  if (!$stmt) {
    die("Error in query preparation: " . $con->error);
  }

  $stmt->execute();

  return $stmt->get_result();
}

function deleteP($id) {
  global $con;
  $query = "DELETE FROM plants WHERE plant_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    return true;
  } else {
  return false;
  }
}


function getPlants(){
  global $con;
  if(isset($_POST["category_id"])) {
      $query = "SELECT * FROM plants WHERE category_id = ?";
      $stmt = $con->prepare($query);  
      $category_id = $_POST["category_id"];
      $stmt->bind_Param("i", $category_id);
      $stmt->execute();
      return $stmt->get_result();
  }else if(isset($_POST["plant_name"])) {
    $query1 = "SELECT * FROM plants WHERE plant_name LIKE ?";
    $stmt = $con->prepare($query1);   
    $plant_name = $_POST["plant_name"];
    $stmt->bind_Param("s", $plant_name);
    $stmt->execute();
    return $stmt->get_result();
  }
  else {
      return getAllP(); 
}
}

?>