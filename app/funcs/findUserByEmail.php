<?php
require_once("./app/config/db.php");

function findUserByEmail($email) {
    global $con;
    $query = "SELECT * FROM users WHERE user_email = ?";
    $stmt = $con->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        }
    }
    return false;
}
?>
