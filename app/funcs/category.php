<?php
require_once("./app/config/db.php");

function addC($newC) {
  global $con;
  $query = "INSERT INTO category(category_name) VALUES(?)";
  $stmt = $con->prepare($query);
  if ($stmt) {
      $stmt->bind_param("s", $newC);
      $stmt->execute();
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

function getAll(){
  global $con;
  $query = "SELECT * FROM category";
  $stmt = $con->prepare($query);
  $stmt->execute();

  return $stmt->get_result();
}

function update($id, $category_name) {
  global $con;
  $query = "UPDATE category SET category_name = ? WHERE category_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("si", $category_name, $id);
  if($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}


function delete($id) {
  global $con;
  $query = "DELETE FROM category WHERE category_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    return true;
  } else {
  return false;
  }
}







    