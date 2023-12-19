<?php 

require_once "../class/DAOs/cartDAO.php";

if (isset($_POST["plant_id"])) {
    $cart = new cartDAO();
    $cart->addToCart($_POST["plant_id"]);
    header('Location: ../home.php');
}