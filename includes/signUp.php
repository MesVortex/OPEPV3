<?php

require_once "../class/userConnection.php";

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){
  $newSignUp = new UserConnection();
  $newSignUp->signUp(trim($_POST['email']), trim($_POST['name']), trim($_POST['password']));
}else{
  $emptyInputsErr = "Please fill out all the fields first!!";
  header("Location: ../index.php?error=".$emptyInputsErr);
}