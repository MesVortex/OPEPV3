<?php

session_start();

require_once "dbConnect.php";

class UserConnection{
  private $DbConnect;

  public function __construct(){
    $this->DbConnect = Database::connectionCheck()->connect();
  }

  public function signUp($email, $name, $pwd){
    if($this->notEmptySignup($email, $name, $pwd)){
      if($this->findUserByEmail($email)) {
        $alreadyExistsErr = "this account already exists!!";
        header("Location: ../index.php?error=".$alreadyExistsErr);
      }else {
        $query = "INSERT INTO users(user_name, user_email, user_password) VALUES(:username, :email, :pwd)";
        $stmt = $this->DbConnect->prepare($query);
        $hashed_pwd = password_hash($pwd, PASSWORD_BCRYPT);
        $stmt->bindParam(":username", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":pwd", $hashed_pwd);
        $stmt->execute();
        $userID = $this->DbConnect->lastInsertId();
        $_SESSION["created_user_id"] = $userID;
        header("Location: ../role.php");
      }
    }else{
      $emptyInputsErr = "Please fill out all the fields first!!";
      header("Location: ../index.php?error=".$emptyInputsErr);
    }
  }

  public function assignRole($roleID){
    $userID = $_SESSION["created_user_id"];
    $query = "UPDATE users
              SET role_id = :roleID
              WHERE user_id = :userID";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":roleID", $roleID);
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
    header("Location: ../index.php");
  }

  public function login($email, $pwd){
    if($this->notEmptyLogin($email, $pwd)){
      if ($this->findUserByEmail($email)) {
        $query = "SELECT * FROM users WHERE user_email = :email";
        $stmt = $this->DbConnect->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($pwd, $user['user_password'])) {
          $_SESSION["logged"] = true;
          $_SESSION["user_id"] = $user["user_id"];
          $_SESSION["role_id"] = $user["role_id"];
        }else{
          $invalidInputsErr = "Your email or password is incorrect!!";
          header("Location: ../index.php?error=".$invalidInputsErr);    
        }
        if ($_SESSION["role_id"] == 1) {
          header("Location: ../home.php");
        }else if ($_SESSION["role_id"] == 2) {
          header("Location: ../dashboard.php");
        }
      }else{
        $invalidInputsErr = "Your email or password is incorrect!!";
        header("Location: ../index.php?error=".$invalidInputsErr);  
      }
    }else{
      $emptyInputsErr = "Please fill out all the fields first!!";
      header("Location: ../index.php?error=".$emptyInputsErr);
    }
  }


  public function logout() {
    unset($_SESSION['logged']);
    unset($_SESSION['user_id']);
    unset($_SESSION['role_id']);
    header("Location: ../index.php");
  }


  public function notEmptySignup($email, $name, $pwd){
    if(empty($email) || empty($name) || empty($pwd)){
      return false;
    }
    return true;
  }
  public function notEmptyLogin($email, $pwd){
    if(empty($email) || empty($pwd)){
      return false;
    }
    return true;
  }

  public function findUserByEmail($email) {
    $query = "SELECT * FROM users WHERE user_email = :email";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0) {
      return true;
    }
    return false;
  }

}