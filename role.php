<?php
session_start();
require_once('./app/config/db.php');
require_once('./app/funcs/updateRole.php');

function assignRole() {
  if (isset($_POST["updateRole"])) {
    if(updateRole($_POST["role_id"], $_SESSION["created_user_id"])){
      if ($_POST["role_id"] == 1) {
        header("Location: home.php");
      }else {
        header("Location: index.php");
      }
    }else {
      die("role update failed");
    }
  }
}

assignRole();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/role.css">
    <title>RolesPage</title>
</head>

<body>

    <form class="container" method="POST" action="./includes/roleAssign.php" id="container">
        <div class="radiocont">
          <div class="form-container user">
          <i class="fas fa-user"></i>
            <label for="client">Client</label>
            <input value="1" name="role_id" type="radio">
          </div>
          <div class="form-container admin">
          <i class="fas fa-user-tie"></i>
            <label for="admin">Admin</label>
            <input value="2" name="role_id"  type="radio">
          </div>
      </div>
        <button class="confirm" type="submit" name="updateRole">Confirm</button>
    </form>
        <!-- <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Already have an accont!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Log In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>First time on O'Pep!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div> -->
    </div>

    <script src="./assets/js/script.js"></script>
</body>

</html>