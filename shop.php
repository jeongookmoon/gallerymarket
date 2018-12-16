<?php
  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 
  require 'navbar.php';
?>

<!-- Page Content -->
<div><p> </p></div>
<div class="container-fluid bg-light">

<div class="row">
  <div class="col-lg-3">
    <h1 class="my-4">Shop By</h1>
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
  
  <div class="col-lg-9">
    <div class="row mx-auto">
    <form class="d-flex justify-content-center" action="shop.php" method="get">
    <?php
        if(isset($_GET['shopby']) && $_GET['shopby'] != "all") {
          $column = $_GET['shopby'];
          buttonsByColumn($conn, $column);
        } else if (isset($_GET['genre'])){
            buttonsByColumn($conn, 'genre');
        } else if (isset($_GET['username'])){
            buttonsByColumn($conn, 'username');
        }
      ?>
      </form>
    </div>
    <div class="row">
    <?php
        if (isset($_GET['shopby']) or isset($_GET['genre']) or isset($_GET['username'])) {
          shopLayout($conn);
        } else if (isset($_GET['column']) && isset($_GET['colVal'])){
          if(!searchResult($conn)){
            header("Location: ../shop.php?searchResult=notFound");
            exit();
          }
        } else {
          echo'<h3 class="text-secondary"> Not found </h3>';
        }
        
    ?>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.col-lg-9 -->
</div>
<!-- /.row -->
</div>
<!-- /.container -->

<?php
function shopLayout($rconn) {
  $conn = $rconn;
  if(isset($_GET['username'])){
    //echo $_GET['username'];
    $user = $_GET['username'];
    $query = "SELECT * FROM (SELECT customerID, imageID, imagePath, genre, price, username, title FROM images NATURAL JOIN customers where forfree = 0) A1 where A1.username = '$user'";
  } else if(isset($_GET['genre'])){
    $genre = $_GET['genre'];
    $query = "SELECT * FROM (SELECT customerID, imageID, imagePath, genre, price, username, title FROM images NATURAL JOIN customers where forfree = 0) A1 where A1.genre = '$genre'";
  } else {
	$query = "SELECT customerID, imageID, imagePath, genre, price, username, title FROM images NATURAL JOIN customers where forfree = 0";
  }

  $result = $conn->query($query);
  
	if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
	
	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);

    echo '<div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-40">
          <a href="product.php?imageID='.$row[1].'"><img class="card-img-top" src="'.$row[2].'" "width="300" height="300" alt=""></a>
          <div class="card-body">
            <h4 class="card-title">
              <a href="#" class="text-secondary">'.$row[6].'</a>
            </h4>
            <h5>$'.$row[4].'</h5>
            <p class="card-text">
            <a href="#" class="text-secondary"> Genre: '.$row[3].'</a>
            </p>
            <p class="card-text">
            <a href="#" class="text-secondary"> Artist: '.$row[5].'</a>
            </p>
          </div>
        </div>
      </div>';
    }
$result->close();
}

function searchResult($rconn) {
  $conn = $rconn;
  // username or genre
  $column = $_GET['column'];
  // value
  $columnValue = $_GET['colVal'];
  $columnValue = strtolower(preg_replace('/\s+/', '', $columnValue));
	$query = "SELECT customerID, imageID, imagePath, genre, price, username, title FROM images NATURAL JOIN customers where $column = '$columnValue' and forfree = 0";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  $resultFlag = 0;
  for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    $resultFlag = 1;
    echo '<div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-40">
          <a href="product.php?imageID='.$row[1].'"><img class="card-img-top" src="'.$row[2].'" "width="300" height="300" alt=""></a>
          <div class="card-body">
            <h4 class="card-title">
              <a href="#" class="text-secondary">'.$row[6].'</a>
            </h4>
            <h5>$'.$row[4].'</h5>
            <p class="card-text">
            <a href="#" class="text-secondary"> Genre: '.$row[3].'</a>
            </p>
            <p class="card-text">
            <a href="#" class="text-secondary"> Artist: '.$row[5].'</a>
            </p>
          </div>
        </div>
      </div>';
  }
  $result->close();
  return $resultFlag;
}

function buttonsByColumn($rconn, $column) {
  $conn = $rconn;
  $query = "SELECT $column FROM images NATURAL JOIN customers where forfree = 0 GROUP BY $column";
  $result = $conn->query($query);

  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  for ($j = 0 ; $j < $rows ; ++$j) {
  $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
      echo'<button class="btn btn-secondary mr-2 my-sm-1" type="submit" name = "'.$column.'" value="'.strtolower($row[0]).'">'.$row[0].'</button>';
  }
  $result->close();
}
?>