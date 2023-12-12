<?php
require_once("./app/config/db.php");

function findPlantInCart($plant_id)
{
  global $con;
  $query = "SELECT * FROM cart_items ci JOIN cart c ON ci.cart_id = c.cart_id WHERE ci.plant_id = ? AND ci.status = 'PENDING' AND c.user_id = ?";
  $stmt = $con->prepare($query);
  $user_id = $_SESSION["user_id"];
  $stmt->bind_param("ii", $plant_id, $user_id);
  $stmt->execute();
  return $stmt->get_result();
}

function updateQte($plant_id, $qte)
{
  global $con;
  $query = "UPDATE cart_items SET quantity = ? WHERE plant_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("ii", $qte, $plant_id);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function checkPlantAvailability($plant_id)
{
  global $con;
  $query = "SELECT quantity FROM plants WHERE plant_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $plant_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $plant = $result->fetch_assoc();
    $available_quantity = $plant['quantity'];

    return $available_quantity > 1;
  }

  return false;
}

function addToCart($plant_id)
{
  global $con;
  $plantResult = findPlantInCart($plant_id);
  if (checkPlantAvailability($plant_id)) {
    if ($plantResult->num_rows > 0) {
      $plant = $plantResult->fetch_assoc();
      if (updateQte($plant_id, $plant["quantity"] + 1)) {
        return true;
      } else {
        return false;
      }
    } else {
      $query1 = "INSERT INTO cart(user_id) VALUES(?)";
      $stmt = $con->prepare($query1);
      $user_id = $_SESSION["user_id"];
      $stmt->bind_param("i", $user_id);

      if ($stmt->execute()) {
        $cart_id = $con->insert_id;
        $query2 = "INSERT INTO cart_items(cart_id , plant_id ) VALUES(?,?)";
        $stmt = $con->prepare($query2);
        $stmt->bind_param("ii", $cart_id, $plant_id);

        if ($stmt->execute()) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
  } else {
    die("Quantity not enough");
  }
}


function cartShow()
{
  global $con;
  $query = "SELECT * FROM plants p JOIN cart_items ci ON p.plant_id = ci.plant_id JOIN cart c ON c.cart_id = ci.cart_id JOIN users u ON u.user_id = c.user_id WHERE c.user_id = ? AND status = 'PENDING'";
  $stmt = $con->prepare($query);
  $user_id = $_SESSION["user_id"];
  $stmt->bind_param("i", $user_id);
  if ($stmt->execute()) {
    return $stmt->get_result();
  } else {
    return false;
  }
}

function clearCart()
{
  global $con;
  $updateQuery = "UPDATE cart_items ci
                    JOIN cart c ON ci.cart_id = c.cart_id
                    SET ci.status = 'SOLD'
                    WHERE c.user_id = ? AND ci.status = 'PENDING'";

  $stmt = $con->prepare($updateQuery);
  $user_id = $_SESSION["user_id"];
  $stmt->bind_param("i", $user_id);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function calculateTotalAmount()
{
  global $con;

  $query = "SELECT SUM(p.plant_price * ci.quantity) AS total_amount
              FROM cart_items ci
              JOIN plants p ON ci.plant_id = p.plant_id
              JOIN cart c ON ci.cart_id = c.cart_id
              WHERE c.user_id = ? AND ci.status = 'PENDING'";

  $stmt = $con->prepare($query);
  $user_id = $_SESSION["user_id"];
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row['total_amount'];
  } else {
    return 0;
  }
}

function updatePlantQuantity()
{
  global $con;

  $updateQuery = "UPDATE plants p
                    JOIN cart_items ci ON p.plant_id = ci.plant_id
                    JOIN cart c ON ci.cart_id = c.cart_id
                    SET p.quantity = p.quantity - ci.quantity
                    WHERE c.user_id = ? AND ci.status = 'SOLD'";

  $stmtUpdate = $con->prepare($updateQuery);
  $user_id = $_SESSION["user_id"];
  $stmtUpdate->bind_param("i", $user_id);

  if (!$stmtUpdate->execute()) {
    return false;
  }

  return true;
}


function order()
{
  global $con;
  $cartItems = cartShow();
  $user_id = $_SESSION["user_id"];
  $totalAmount = calculateTotalAmount($user_id);
  if ($cartItems->num_rows > 0) {
    $insertOrderQuery = "INSERT INTO orders (user_id, total_amount, cart_item_id) VALUES (?, ?, ?)";
    $stmtOrder = $con->prepare($insertOrderQuery);

    foreach ($cartItems as $cartItem) {
      $cartItemID = $cartItem['cartitem_id'];

      $stmtOrder->bind_param("iii", $user_id, $totalAmount, $cartItemID);
      $stmtOrder->execute();
    }
    if (clearCart()) {
      if (updatePlantQuantity()) {
        return true;
      }
    }
  }
}

function deleteAllPlantsInCart()
{
    global $con;

    $deleteQuery = "DELETE FROM cart_items WHERE status = 'PENDING' AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = ?)";

    $stmt = $con->prepare($deleteQuery);
    $user_id = $_SESSION["user_id"];
    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        return false;
    }

    return true;
}

