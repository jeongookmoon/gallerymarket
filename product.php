<?php
  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 
  require 'navbar.php';
  makeTransaction($conn);
  function makeTransaction($rconn) {
    $conn = $rconn;
    if (isset($_POST['purchase'])) {
      $customerID = $_SESSION['customerID'];
      $imageID = get_post($conn, 'purchase');
      $custCredit = getCustCredit($conn);
      $imgPrice = getImagePrice($conn);
      if($custCredit[0] >= $imgPrice[0]) {
        $query = "INSERT INTO transactions VALUES('$customerID', '$imageID', now())";
        $result = $conn->query($query);
        if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";    
        
        $query = "UPDATE customers SET credit = credit - '$imgPrice[0]' where customerID = '$customerID'";
        $result = $conn->query($query);
        if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";      

        header("Location: product.php?imageID=$imageID&result=success");
        exit();
      } else {
        echo '<div class="container-fluid bg-light"><p class="text-danger">Not enough credit to make a purchase</p></div>';
      }
      
    }
  }

  function getImagePrice($rconn){
    $conn = $rconn;
    $imgID = get_post($conn, 'purchase');
    $query = "SELECT price FROM images where imageID = '$imgID'";
    $result = $conn->query($query);
  
    if (!$result) die ("Database access failed: " . $conn->error);
  
    $rows = $result->num_rows;
    
    $info = [];
    for ($j = 0 ; $j < $rows ; ++$j) {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
      $info = $row;
    }
        
    $result->close();
    return $info;
  }

  function getCustCredit($rconn){
    $conn = $rconn;
    $customerID = $_SESSION['customerID'];
    $query = "SELECT credit FROM customers where customerID = '$customerID'";
    $result = $conn->query($query);
  
    if (!$result) die ("Database access failed: " . $conn->error);
  
    $rows = $result->num_rows;
    
    $info = [];
    for ($j = 0 ; $j < $rows ; ++$j) {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
      $info = $row;
    }
        
    $result->close();
    return $info;
  }
?>
<?php 
if(isset($_SESSION['customerID']) && isset($_SESSION['username'])){
?>
<!-- Page Content -->
<div><p> </p></div>
<div class="container-fluid bg-light">

<div class="row">

  <div class="col-lg-3">
    <h1 class="my-4">Image Detail</h1>
    <div class="list-group">
    <a href="shop.php?shopby=all" class="list-group-item">All Images</a>
      <a href="shop.php?shopby=genre" class="list-group-item">Genre</a>
      <a href="shop.php?shopby=username" class="list-group-item">Artist</a>
      <p>
      <form action="" method="get" id="searchForm" class="input-group form-inline my-2 my-lg-0">                   
        <div class="input-group-btn search-panel">
            <select name="column" id="column" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <option value="username">Username</option>
                <option value="genre">Genres</option>
            </select>
        </div>
        <input type="text" class="form-control" name="colVal">
        <span class="input-group-btn">
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
        </span>
      </form>
      </p>
    </div>
  </div>
  <!-- /.col-lg-3 -->

  <div class="col-lg-9">
  <?php
     if (isset($_GET['imageID'])){
       productLayoutByImageID($conn);
    } else {

    }
  ?>
  </div>
  <!-- /.col-lg-9 -->

</div>

</div>
<!-- /.container -->


  </div>
  <!-- /.col-lg-9 -->
</div>
<!-- /.row -->
</div>
<!-- /.container -->
<?php
} else {
  header("Location: ../index.php?error=not_logged_in");
  $conn->close();
  exit();
}
?>
<?php
function productLayoutByImageID($rconn) {
  $conn = $rconn;
  $imageID = $_GET['imageID'];
	$query = "SELECT customerID, imageID, imagePath, genre, price, username, title FROM images NATURAL JOIN customers where imageID = '$imageID'";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	
	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);
    echo'<div class="card mt-4">
          <img class="card-img-top img-fluid" src="'.$row[2].'" alt="">
          <div class="card-body">
            <h3 class="card-title"><a href="#" class="text-secondary">Title: '.$row[6].'</a></h3>
            <h4>$'.$row[4].'</h4>
            <p class="card-text">Genre: '.$row[3].'</p>
            <p class="card-text">Artist: '.$row[5].'</p>
            <form class="form-inline my-2 my-lg-0" action="product.php?imageID='.$row[1].'" method="post">
            <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit" name="purchase" value='.$row[1].'>Purchase</button>
            </form>
            </div>
        </div>';
    }
$result->close();
}