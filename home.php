<?php
session_start();

require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/plantDAO.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/cartDAO.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/categoryDAO.php');

if (isset($_SESSION["logged"]) && $_SESSION["logged"] && isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 2) {
  header("Location: dashboard.php");
}else if(!$_SESSION["logged"]){
  header("Location: index.php");
}

$categoryObj = new CategoryDAO();
$plantsObj = new PlantDAO();
$cartObj = new cartDAO();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" href="assets/imgs/logoG.png" type="image/x-icon">

  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

  <link rel="stylesheet" href="./assets/css/home.css">

  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    >

  <title>Home</title>
</head>

<body>
  <header class="header" id="header">
    <nav class="nav container">
      <a href="#" class="nav__logo">
        <img src="./assets/imgs/logoG.png" alt="logo">
      </a>
      <div class="search">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input name="plant_name" type="text" placeholder="Search here">
          <i class="ri-search-2-line"></i>
        </form>
      </div>
      <div class="nav__menu" id="nav-menu">
        <ul class="nav__list">
          <li class="nav__item">
            <a href="#home" class="nav__link active-link">Home</a>
          </li>
          <li class="nav__item">
            <a href="#products" class="nav__link">Products</a>
          </li>
          <!-- shopping cart -->
          <li>
            <a href="./blog.php" class="button--flex navbar__button">
              <i class="fa-brands fa-hive"></i>
            </a>
          </li>
          <li>
            <button class="button--flex navbar__button cart-open">
              <i class="ri-shopping-bag-line"></i>
            </button>
          </li>
          <!-- log out -->
          <li>
            <form method="post" action="./includes/logout.php">
              <button type="submit" name="logout" class="button--flex navbar__button">
                <i class="ri-logout-box-r-line"></i>
              </button>
            </form>
          </li>
        </ul>


      </div>
    </nav>
    <div class="popup-container">
      <div class="cart_popup">
        <button class="cart-close">
          <i class="ri-close-circle-line"></i>
        </button>
        <img class="logo" src="./assets/imgs/logoW.png" alt="">
        <h1>Your Plants</h1>
        <ul class="cartItemsList">
          <?php 
            $cart = $cartObj->cartShow($_SESSION["user_id"]);
            $cartItems = $cart->getPlants();
            foreach ($cartItems as $cartItem) {
          ?>
            <li>
              <div class="pic">
                <img src="./assets/imgs/<?php echo $cartItem->getIMG(); ?>" alt="">
              </div>
              <div class="info">
                <p><?php echo $cartItem->getName(); ?></p>
              </div>
              <div class="price">
                <p><?php echo $cartItem->getPrice(); ?>$</p>
              </div>
              <div class="removePlant">
                <p><?php echo $cartItem->getQuantity(); ?></p>
              </div>
            </li>
          <?php

          }
          ?>

        </ul>
        <div class="check-out">
          <div class="total"><?php echo $cartObj->calculateTotalAmount(); ?> $</div>

          <form method="post" action="./includes/checkout.php">
            <button name="order" class="check">Check Out</button>
          </form>

          <form method="post" action="./includes/clearCart.php">
            <button type="submit" name="clear" class="clear">Clear All</button>
          </form>

        </div>
      </div>
    </div>
  </header>


  <main class="main">

    <section class="home" id="home">
      <div class="home__container container grid">
        <img src="assets/imgs/home.png" alt="" class="home__img">

        <div class="home__data">
          <h1 class="home__title">
            Plants will make <br> your life better
          </h1>
          <p class="home__description">
            Create incredible plant design for your offices or apastaments.
            Add fresness to your new ideas.
          </p>
          <a href="#products" class="button button--flex">
            Explore <i class="ri-arrow-right-down-line button__icon"></i>
          </a>
        </div>


      </div>
    </section>

    <section class="product section container" id="products">
      <h2 class="section__title-center">
        Check out our products
      </h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="select-container">
        <select class="form-select" name="category_id" id="category">
          <option value="0" selected>Plant Category</option>
          <?php 
            $categories = $categoryObj->getAllCategories();
            foreach ($categories as $category) { 
          ?>
            <option value="<?php echo $category->getID(); ?>">
              <?php echo $category->getName(); ?>
            </option>
          <?php } ?>
        </select>
        <div class="icon-container">
          <i class="ri-arrow-down-s-fill"></i>
        </div>
        <button class="select-btn" type="submit">Filter</button>
      </form>
      <div class="product__container grid">

        <?php 
          if(isset($_POST["category_id"])) {
            $plants = $plantsObj->getPlantsByCategory($_POST["category_id"]);
          }else if(!empty($_POST["plant_name"])) {
            $plants = $plantsObj->getPlantsByName($_POST["plant_name"]);
          }else{
            $plants = $plantsObj->getAllPlants();
          }
          foreach ($plants as $plant) {
        ?>
          <article class="product__card">
            <div class="product__circle"></div>

            <img src="assets/imgs/<?php echo $plant->getIMG(); ?>" alt="" class="product__img">

            <h3 class="product__title"><?php echo $plant->getName(); ?></h3>
            <span class="product__price"><?php echo $plant->getPrice(); ?>$</span>
            <form action="./includes/addToCart.php" method="post">
              <input name="plant_id" type="hidden" value="<?php echo $plant->getID() ?>">
              <button name="add" type="submit" class="button--flex product__button">
                <i class="ri-shopping-bag-line"></i>
              </button>
            </form>
          </article>
        <?php
        }    ?>

    </section>

  </main>

  <footer class="footer section">
    <div class="footer__container container grid">
      <div class="footer__content">
        <a href="#" class="nav__logo">
          <img src="./assets/imgs/logoG.png" alt="">
        </a>




      </div>

      <div class="footer__content">
        <h3 class="footer__title">Our Address</h3>

        <ul class="footer__data">
          <li class="footer__information">1234 - Safi</li>
          <li class="footer__information">P2306</li>

        </ul>
      </div>

      <div class="footer__content">
        <h3 class="footer__title">Contact Us</h3>

        <ul class="footer__data">
          <li class="footer__information">0617196324</li>

          <div class="footer__social">
            <a href="https://www.facebook.com/" class="footer__social-link">
              <i class="ri-facebook-fill"></i>
            </a>
            <a href="https://www.instagram.com/" class="footer__social-link">
              <i class="ri-instagram-line"></i>
            </a>
            <a href="https://twitter.com/" class="footer__social-link">
              <i class="ri-twitter-fill"></i>
            </a>
          </div>
        </ul>
      </div>
    </div>


  </footer>



  <script src="./assets/js/home.js"></script>
</body>

</html>