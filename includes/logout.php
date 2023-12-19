<?php

require_once "../class/userConnection.php";

  if (isset($_POST["logout"])) {
    $user = new UserConnection();
    $user->logout();
  }
