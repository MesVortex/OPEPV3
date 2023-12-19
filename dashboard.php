<?php
session_start();
if(isset($_SESSION["logged"]) && $_SESSION["logged"] && isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 1) {
  header("Location:home.php");
}
require_once('./app/config/db.php');
// require_once("./app/funcs/category.php");
// require_once('./app/funcs/plant.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/plantDAO.php');
require_once ('/xampp/htdocs/brief8/OPEPV3/class/DAOs/categoryDAO.php');
require_once('./app/funcs/logout.php');

function handleCategory()
{
  if (isset($_POST["addCategory"])) {
    addC($_POST["categoryName"]);
  } else if (isset($_POST["deleteCategory"])) {
    delete($_POST["category_id"]);
  }else if(isset($_POST["updateCategoryName"])) {
    update($_POST["updatedCategoryID"], $_POST["newCategoryName"]);
  }
}

handleCategory();

// $categories = getAll();

function handlePlant()
{
  if (isset($_POST["addPlant"])) {
    $data = [
      "plant_name" => trim($_POST['plant_name']),
      "plant_img" => $_FILES['plant_img']['name'],
      "plant_price" => trim($_POST['plant_price']),
      "category_id" => $_POST['category_id'],
    ];
    addP($data);
  } else if (isset($_POST["deletePlant"])) {
    deleteP($_POST["plant_id"]);
  }
}
handlePlant();

// $plants = getAllP();
$plantsObj = new PlantDAO();
$categoryObj = new CategoryDAO();


function adminLogout() {
  if (isset($_POST["logout"])) {
   
    if (logout()) {
      // die("here");
      header("Location: index.php");
    }
  }
}

adminLogout();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/dashboard.css">
  <link rel="shortcut icon" href="assets/imgs/logoG.png" type="image/x-icon">
  <title>Admin Dashboard</title>
</head>

<body>
  <div class="container">
    <div class="navigation">
      <ul>
        <li class="logo">
          <a href="#">
            <span class="icon">
              <img src="./assets/imgs/logoWo.png" alt="">
            </span>
            <span class="title"><img src="./assets/imgs/logoWw.png" alt=""></span>
          </a>
        </li>

        <li>
          <a href="#">
            <span class="icon">
              <ion-icon name="home-outline"></ion-icon>
            </span>
            <span class="title">Dashboard</span>
          </a>
        </li>

        <li>
          <a href="#">
            <span class="icon">
              <ion-icon name="albums-outline"></ion-icon>
            </span>
            <span class="title">Categories</span>
          </a>
        </li>

        <li>
          <a href="#">
            <span class="icon">
              <ion-icon name="leaf-outline"></ion-icon>
            </span>
            <span class="title">Plants</span>
          </a>
        </li>

        <li>
          <a href="#">
            <span class="icon">
              <ion-icon name="file-tray-full-outline"></ion-icon>
            </span>
            <span class="title">Orders</span>
          </a>
        </li>

        <li>
          <a href="#">
            <span class="icon">
              <ion-icon name="settings-outline"></ion-icon>
            </span>
            <span class="title">Settings</span>
          </a>
        </li>
        <li>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
            <span class="icon">
              <ion-icon name="log-out-outline"></ion-icon>
            </span>
            <button type="submit" name="logout" class="title">log Out</button>
          </form>
        </li>
      </ul>
    </div>

    <main class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>

      

        <div class="user">
          <img src="assets/imgs/customer01.jpg" alt="">
        </div>
      </div>

      <div class="cardBox">
        <div class="card">
          <div>
            <div class="numbers">4</div>
            <div class="cardName">Category</div>
          </div>

          <div class="iconBx">
            <ion-icon name="albums-outline"></ion-icon>
          </div>
        </div>

        <div class="card">
          <div>
            <div class="numbers">10</div>
            <div class="cardName">Plants</div>
          </div>

          <div class="iconBx">
            <ion-icon name="leaf-outline"></ion-icon>
          </div>
        </div>

        <div class="card">
          <div>
            <div class="numbers">155</div>
            <div class="cardName">Clients</div>
          </div>

          <div class="iconBx">
          <ion-icon name="people-outline"></ion-icon>
          </div>
        </div>

        <div class="card">
          <div>
            <div class="numbers">23</div>
            <div class="cardName">Order</div>
          </div>

          <div class="iconBx">
            <ion-icon name="file-tray-full-outline"></ion-icon>
          </div>
        </div>
      </div>

      <div class="details">
        <div class="recentOrders">
          <div class="cardHeader">
            <h2>Plant's Categories</h2>
            <a href="#" class="btn" onclick="openPopupC()">Add Category</a>
          </div>
          <div id="categoryPopup" class="popup">
            <form class="popup-content" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
              <span class="close" onclick="closePopupC()">&times;</span>
              <label for="categoryName">Category Name:</label>
              <input type="text" id="categoryName" name="categoryName">
              <button type="submit" name="addCategory">Add</button>
            </form>
          </div>
          <table>
            <thead>
              <tr>
                <td>Name</td>
                <td>Operations</td>
              </tr>
            </thead>
            <tbody>
              <?php 
              $categories = $categoryObj->getAllCategories();
              foreach ($categories as $category) {
              ?>
                <tr>
                  <td><?php echo $category->getName() ?></td>
                  <td class="btns">
                    <input type="hidden" value="<?php echo $category->getID(); ?>">
                  <button name="modifyCategory" class="btn update_btn">Modify</button>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                      <input id="updateCategoryName" name="category_id" type="hidden" value="">
                      <button name="deleteCategory"  class="btn bred" type="submit">Delete</button>
                    </form>
                    
                  </td>
                </tr>
              <?php
              }    ?>
              <div id="modifyPopup" class="popup">
                      <form class="popup-content" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <span class="close" onclick="closeModifyPopup()">&times;</span>
                        <label for="categoryName">New Category Name:</label>
                        <input name="updatedCategoryID" id="categoryID" type="hidden" value="">
                        <input name="newCategoryName" type="text" id="categoryNameModify" >
                        <button name="updateCategoryName" type="submit">Change</button>
                      </form>
                </div>
            </tbody>
          </table>
        </div>
        <div class="recentOrders">
          <div class="cardHeader">
            <h2>Plants</h2>
            <a href="#" class="btn" onclick="openPopupP()">Add Plant</a>
          </div>
          <div id="plantPopup" class="popup">
            <form class="popup-content" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
              <span class="close" onclick="closePopupP()">&times;</span>
              <label for="plantName">Plant Name:</label>
              <input type="text" id="plantName" name="plant_name">
              <label for="plantImg">Plant Image:</label>
              <input type="file" id="plantImg" name="plant_img">
              <label for="plantPrice">Plant Price:</label>
              <input type="number" id="plantPrice" name="plant_price">
              <label for="categoryName">Plant category:</label>
              <select name="category_id">
                <?php foreach ($categories as $category) {
                ?>
                  <option value="<?php echo $category->getID(); ?>"><?php echo $category->getName(); ?></option>
                <?php  } ?>
              </select>
              <button type="submit" name="addPlant">Add</button>
            </form>
          </div>
          <table>
            <thead>
              <tr>
                <td>Image</td>
                <td>Name</td>
                <td>Price</td>
                <td>Catgory</td>
                <td>Operations</td>
              </tr>
            </thead>
            <tbody>
              <?php 
              $plants = $plantsObj->getAllPlants();
              foreach ($plants as $plant) {
              ?>
                <tr>
                  <td><img style="width: 50px;" src="./assets/imgs/<?php echo $plant->getIMG(); ?>" alt=""></td>
                  <td><?php echo $plant->getName(); ?></td>
                  <td><?php echo $plant->getPrice(); ?>$</td>
                  <td><?php echo $plant->getCategoryID(); ?></td>
                  <td>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                      <input name="plant_id" type="hidden" value="<?php echo $plant->getID(); ?>">
                      <button name="deletePlant" class="btn bred" type="submit">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php
              }    ?>

            </tbody>
          </table>
        </div>
        <!-- <div class="recentOrders">
          <div class="cardHeader">
            <h2>Recent Orders</h2>
            <div>
              <a href="#" class="btn">View All</a>
              <a href="#" class="btn">View All</a>
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <td>Name</td>
                <td>Price</td>
                <td>Payment</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Star Refrigerator</td>
                <td>$1200</td>
                <td>Paid</td>
                <td><span class="status delivered">Delivered</span></td>
              </tr>
              <tr>
                <td>Dell Laptop</td>
                <td>$110</td>
                <td>Due</td>
                <td><span class="status pending">Pending</span></td>
              </tr>
              <tr>
                <td>Apple Watch</td>
                <td>$1200</td>
                <td>Paid</td>
                <td><span class="status return">Return</span></td>
              </tr>
              <tr>
                <td>Addidas Shoes</td>
                <td>$620</td>
                <td>Due</td>
                <td><span class="status inProgress">In Progress</span></td>
              </tr>
              <tr>
                <td>Star Refrigerator</td>
                <td>$1200</td>
                <td>Paid</td>
                <td><span class="status delivered">Delivered</span></td>
              </tr>
              <tr>
                <td>Dell Laptop</td>
                <td>$110</td>
                <td>Due</td>
                <td><span class="status pending">Pending</span></td>
              </tr>
              <tr>
                <td>Apple Watch</td>
                <td>$1200</td>
                <td>Paid</td>
                <td><span class="status return">Return</span></td>
              </tr>
              <tr>
                <td>Addidas Shoes</td>
                <td>$620</td>
                <td>Due</td>
                <td><span class="status inProgress">In Progress</span></td>
              </tr>
            </tbody>
          </table>
        </div> -->
      </div>
    </main>
  </div>


  <script src="./assets/js/main.js"></script>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>