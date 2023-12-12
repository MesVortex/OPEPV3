<?php

function logout() {
  unset($_SESSION['logged']);
  unset($_SESSION['user_id']);
  unset($_SESSION['role_id']);
  // session_destroy();
  return true;
}
?>
