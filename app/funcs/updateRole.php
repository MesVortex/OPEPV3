<?php
require_once("./app/config/db.php");
function updateRole($role_id, $user_id)
{
  global $con;
  $query = "UPDATE users
    SET role_id = ?
    WHERE user_id = ?;
    ";
  $stmt = $con->prepare($query);
  $stmt->bind_param("ii", $role_id, $user_id);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}
