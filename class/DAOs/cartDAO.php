<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once ('/xampp/htdocs/brief8/OPEPV3/class/dbConnect.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/cart.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/plant.php');


class cartDAO{
  private $DbConnect;

  public function __construct(){
    $this->DbConnect = Database::connectionCheck()->connect();
  }


  public function cartShow($userID){
    $query = "SELECT * FROM plants p 
              JOIN cart_items ci 
              ON p.plant_id = ci.plant_id 
              JOIN cart c 
              ON c.cart_id = ci.cart_id 
              JOIN users u 
              ON u.user_id = c.user_id 
              WHERE c.user_id = :userID
              AND 
              status = 'PENDING'";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
    $DbPlants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $plants = array();
    foreach($DbPlants as $P){
      $plants[] = new Plant($P['plant_id'], $P['plant_name'], $P['category_id'], $P['plant_img'], $P['plant_price'], $P['quantity']);
    }
    $cart = new Cart($userID, $plants);
    return $cart;
  }


  public function addToCart($plantID){
    $plant = $this->findPlantInCart($plantID);
    if ($this->checkPlantAvailability($plantID)) {
      if (count($plant) > 0) {
        $this->updateQte($plantID, $plant["quantity"] + 1);
      } else {
        $query = "INSERT INTO cart(user_id) VALUES(:userID)";
        $stmt = $this->DbConnect->prepare($query);
        $userID = $_SESSION["user_id"];
        $stmt->bindParam(":userID", $userID);
        $stmt->execute();
  
        $cartID = $this->DbConnect->lastInsertId();
        $query2 = "INSERT INTO cart_items(cart_id , plant_id ) VALUES(:cartID, :plantID)";
        $stmt2 = $this->DbConnect->prepare($query2);
        $stmt2->bindParam(":cartID", $cartID);
        $stmt2->bindParam(":plantID", $plantID);
        $stmt2->execute();
      }
    } else {
      die("Quantity not enough");
    }
  }


  public function order(){
    $userID = $_SESSION["user_id"];
    $cartItems = $this->cartShow($userID)->getPlants();
    $cartItemIDs = $this->cartItemId($userID);
    $totalAmount = $this->calculateTotalAmount();
    if (count($cartItems) > 0) {
      $insertOrderQuery = "INSERT INTO orders (user_id, total_amount, cart_item_id) VALUES (:userID, :total, :cartplants)";
      $stmtOrder = $this->DbConnect->prepare($insertOrderQuery);
      $i = 0;

      foreach ($cartItems as $cartItem) {
        $cartItemID = $cartItemIDs[$i];

        $stmtOrder->bindParam(":userID", $userID);
        $stmtOrder->bindParam(":total", $totalAmount);
        $stmtOrder->bindParam(":cartplants", $cartItemID);
        $stmtOrder->execute();

        $i++;
      }
      $this->sellPlants();
      $this->updatePlantQuantity();
    }
  }


  public function deleteAllPlantsInCart(){
    $deleteQuery = "DELETE FROM cart_items WHERE status = 'PENDING' AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = :userID)";
    $stmt = $this->DbConnect->prepare($deleteQuery);
    $userID = $_SESSION["user_id"];
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
  }


  public function cartItemId($userID){
    $query = "SELECT * FROM plants p 
                JOIN cart_items ci 
                ON p.plant_id = ci.plant_id 
                JOIN cart c 
                ON c.cart_id = ci.cart_id 
                JOIN users u 
                ON u.user_id = c.user_id 
                WHERE c.user_id = :userID
                AND 
                status = 'PENDING'";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $cartItemIDs = array();
    foreach($result as $ID){
      $cartItemIDs[] = $ID['cartitem_id'];
    }
    return $cartItemIDs;
  }


  public function findPlantInCart($plantID){
    $query = "SELECT * FROM cart_items ci JOIN cart c ON ci.cart_id = c.cart_id WHERE ci.plant_id = :plantID AND ci.status = 'PENDING' AND c.user_id = :userID";
    $stmt = $this->DbConnect->prepare($query);
    $userID = $_SESSION["user_id"];
    $stmt->bindParam(":plantID", $plantID);
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function checkPlantAvailability($plantID){
    $query = "SELECT quantity FROM plants WHERE plant_id = :plantID";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":plantID", $plantID);
    $stmt->execute();
    $plant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (count($plant) > 0) {
      $available_quantity = $plant['quantity'];
      if($available_quantity > 1){
        return true;
      }
    }
    return false;
  }


  public function updateQte($plantID, $qte){
    $query = "UPDATE cart_items SET quantity = :quantity WHERE plant_id = :plantID";
    $stmt = $this->DbConnect->prepare($query);
    $stmt->bindParam(":plantID", $plantID);
    $stmt->bindParam(":quantity", $qte);
    $stmt->execute();
  }


  public function calculateTotalAmount(){
    $query = "SELECT SUM(p.plant_price * ci.quantity) AS total_amount
                FROM cart_items ci
                JOIN plants p ON ci.plant_id = p.plant_id
                JOIN cart c ON ci.cart_id = c.cart_id
                WHERE c.user_id = :userID AND ci.status = 'PENDING'";

    $stmt = $this->DbConnect->prepare($query);
    $userID = $_SESSION["user_id"];
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
      return $result['total_amount'];
    } else {
      return 0;
    }
  }

  public function updatePlantQuantity(){
    $updateQuery = "UPDATE plants p
                      JOIN cart_items ci ON p.plant_id = ci.plant_id
                      JOIN cart c ON ci.cart_id = c.cart_id
                      SET p.quantity = p.quantity - ci.quantity
                      WHERE c.user_id = :userID AND ci.status = 'SOLD'";

    $stmtUpdate = $this->DbConnect->prepare($updateQuery);
    $userID = $_SESSION["user_id"];
    $stmtUpdate->bindParam(":userID", $userID);
    $stmtUpdate->execute();
  }

  public function sellPlants(){
    $updateQuery = "UPDATE cart_items ci
                      JOIN cart c ON ci.cart_id = c.cart_id
                      SET ci.status = 'SOLD'
                      WHERE c.user_id = :userID AND ci.status = 'PENDING'";

    $stmt = $this->DbConnect->prepare($updateQuery);
    $userID = $_SESSION["user_id"];
    $stmt->bindParam(":userID", $userID);
    $stmt->execute();
  }

}