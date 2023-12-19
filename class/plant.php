<?php

class Plant {
  private $ID;
  private $name;
  private $categoryID;
  private $IMG;
  private $price;
  private $quantity;

  public function __construct($ID, $name, $categoryID, $IMG, $price, $quantity){
    $this->ID = $ID;
    $this->name = $name;
    $this->categoryID = $categoryID;
    $this->IMG = $IMG;
    $this->price = $price;
    $this->quantity = $quantity;
  }

  // SETTERS

  // public function setID($newID){
  //   return $this->ID = $newID;
  // }
  // public function setName($newName){
  //   return $this->name = $newName;
  // }
  // public function setCategoryID($newCategoryID){
  //   return $this->categoryID = $newCategoryID;
  // }
  // public function setIMG($newIMG){
  //   return $this->IMG = $newIMG;
  // }
  // public function setPrice($newPrice){
  //   return $this->price = $newPrice;
  // }
  // public function setQuantity($newQuantity){
  //   return $this->quantity = $newQuantity;
  // }

  // GETTERS

  public function getID(){
    return $this->ID;
  }
  public function getName(){
    return $this->name;
  }
  public function getCategoryID(){
    return $this->categoryID;
  }
  public function getIMG(){
    return $this->IMG;
  }
  public function getPrice(){
    return $this->price;
  }
  public function getQuantity(){
    return $this->quantity;
  }
}