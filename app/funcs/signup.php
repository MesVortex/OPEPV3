  <?php
  require_once("./app/config/db.php");
  require_once("./app/funcs/findUserByEmail.php");

  function signup($data) {
    global $con;
    if(findUserByEmail($data["email"])) {
      return false;
    }else {
      $query = "INSERT INTO users(user_name, user_email, user_password) VALUES(?, ?, ?)";
  $stmt = $con->prepare($query);
  if ($stmt) {
    $hashed_password = password_hash($data["password"], PASSWORD_BCRYPT);
    $stmt->bind_param("sss", $data["name"], $data["email"], $hashed_password);  

      if ($stmt->execute()) {
        $user_id = $con->insert_id;
        $_SESSION["created_user_id"] = $user_id;
        return true;
      } else {
          return false;
      }
  } else {
      return false;
  } 

    }
  }
  ?>