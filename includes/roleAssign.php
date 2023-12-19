<?php 

require_once "../class/userConnection.php";

if(isset($_POST['role_id'])){
  $AssignRole = new UserConnection();
  $AssignRole->assignRole($_POST['role_id']);
}else{
  $UnknownErr = "ERROR!!";
  header("Location: ../index.php?error=".$UnknownErr);
}