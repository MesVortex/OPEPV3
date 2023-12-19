<?php

class User{
  private $ID;
  private $name;
  private $email;
  private $pwd;
  private $roleID;

  public function __construct($name, $email, $pwd){
    $this->name = $name;
    $this->email = $email;
    $this->pwd = $pwd;
  }
}