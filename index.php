<?php
session_start();
if(isset($_SESSION["logged"]) && $_SESSION["logged"] && isset($_SESSION["role_id"])) {
    if ($_SESSION["role_id"] == 1) {
        header("Location: home.php");
    } else if ($_SESSION["role_id"] == 2) {
        header("Location: dashboard.php");
    }
}
// require_once('./app/config/db.php');
// require_once('./app/funcs/signup.php');
// require_once('./app/funcs/login.php');


// function register() {
    // if (isset($_POST["signup"])) {
        // $data = [
        //     "name" => trim($_POST['name']),
        //     "email" => trim($_POST['email']),
        //     "password" => trim($_POST['password']),
        // ];
        // if(signup($data)) {
        //     header("Location: role.php");
        // }else{
        //     die("signup failed");
        // }
// if  (isset ($_POST["login"])){
//        if (login($_POST['email'], $_POST['password'])){
//         if ($_SESSION["role_id"] == 1) {
//             header("Location: home.php");
//         } else if ($_SESSION["role_id"] == 2) {
//             header("Location: dashboard.php");
//         }
//        }else {
//             die("not valid");
//        }
//     }
// }


// register();
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="shortcut icon" href="assets/imgs/logoG.png" type="image/x-icon">
    <title>Register Page</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post" action="./includes/signUp.php">
                <h1>Create Account</h1>
                <div class="inputsdiv">
                  <div>
                    <input name="name" type="text" placeholder="Name">
                    <input name="email" type="email" placeholder="Email">
                    <div id="email-error" class="error-message"></div>

                    <input name="password" type="password" placeholder="Password">
                  </div>
                  <button type="submit">Sign Up</button>
              </div>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post" action="./includes/logIn.php">
                <h1>Log In</h1>
                <div class="inputsdiv">
                  <div>
                    <input name="email" type="email" placeholder="Email">
                    <input name="password" type="password" placeholder="Password">
                  </div>
                  <button type="submit" name="login">Log In</button>
              </div>
            </form>
        </div>
        <div class="toggle-container">
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
        </div>
    </div>

    <script src="./assets/js/script.js"></script>
</body>

</html>