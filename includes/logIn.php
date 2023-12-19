<?php

require_once "../class/userConnection.php";

if(isset($_POST['email']) && isset($_POST['password'])){
  $newLogin = new UserConnection();
  $newLogin->login(trim($_POST['email']), trim($_POST['password']));
}else{
  $emptyInputsErr = "Please fill out all the fields first!!";
  header("Location: ../index.php?error=".$emptyInputsErr);
}