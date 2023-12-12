<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// session_start();
require_once("./app/config/db.php");
require_once("./app/funcs/findUserByEmail.php");

function login($email, $password) {
    
    if (findUserByEmail($email)) {
        global $con;
        $query = "SELECT * FROM users WHERE user_email = ?";
        $stmt = $con->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc(); 
                if (password_verify($password, $user['user_password'])) {
                    $_SESSION["logged"] = true;
                    $_SESSION["user_id"] = $user["user_id"];
                    $_SESSION["role_id"] = $user["role_id"];
                    $stmt->close();
                    return $user;
                }
            }
            $stmt->close();
        }
    }
    return false;
}
?>
