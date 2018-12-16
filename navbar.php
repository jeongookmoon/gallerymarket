<?php
  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 
  require 'behind/dbhandler.php';

  function getCredit($rconn, $customerID){
    $conn = $rconn;
    $query = "SELECT credit FROM customers where customerID = '$customerID'";
    $result = $conn->query($query);
  
    if (!$result) die ("Database access failed: " . $conn->error);
  
    $rows = $result->num_rows;
    
    $row = "";
    for ($j = 0 ; $j < $rows ; ++$j) {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
    }
        
    $result->close();
    return $row[0];
  }
  
  // echo session_id();
  // echo "<br>";
  // echo ini_get('session.cookie_domain');
  // echo "<br>";
  // print_r($_SESSION['login_uid']);
  // echo "<br>";
  // print_r($_SESSION['login_pwd']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery Market</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/lightbox.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="css/shop-item.css" >
    <link rel="stylesheet" href="css/w3.css">
</head>
<body>

<!-- navbar below was built using bootstrap documentation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">
  <!-- watermark img from https://www.freeiconspng.com/img/13229 -->
      <img src="img/watermark.png" width="30" height="30" class="d-inline-block align-top" alt="">
      Gallery Market
    </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <?php
        if(isset($_SESSION['customerID']) && isset($_SESSION['username'])){
          $username = $_SESSION['username'];
          $customerID = $_SESSION['customerID'];
          $credit = getCredit($conn, $customerID);
          echo '<li><p class="navbar text-secondary">'.$username.'($'.$credit.')</p></li> <li><a href="charge.php?username='.$username.'" class="btn btn-secondary center">charge</a></li>';
          echo '<li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Menu
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="gallery.php">Gallery</a>
              <a class="dropdown-item" href="upload.php">Upload</a>
              <a class="dropdown-item" href="shop.php?shopby=all">Shop</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="myaccount.php?username='.$username.'">Account</a>
            </div>
          </li>
          <li><form class="form-inline my-2 my-lg-0" action="behind/logout.bhd.php" method="post">
            <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit" name="logout_submit">Logout</button>
          </form></li>';
        } else {
          echo '<form class="form-inline my-2 my-lg-0" action="behind/login.bhd.php" method="post">
          <input class="form-control mr-sm-2" type="text" name="login_uid" placeholder="Username/E-mail">
          <input class="form-control mr-sm-2" type="password" name="login_pwd" placeholder="Password">
          <button class="btn btn-outline-secondary my-2 my-sm-0" name="login_submit" type="submit">Login</button>
          </form>
          <form class="form-inline my-2 my-lg-0" action="signup.php">
          <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">SignUp</button>
          </form>';
        }
      ?>
    </ul>
  </div>
</nav>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="js/lightbox-plus-jquery.min.js"></script>
</body>
</html>