<?php
  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 
  require 'navbar.php';
  deleteImage($conn);
  function getUserInfo($rconn, $customerID){
    $conn = $rconn;
    $query = "SELECT name, email, username, continent, age, credit FROM customers where customerID = '$customerID'";
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

  function deleteImage($rconn){
	$conn = $rconn;
	if (isset($_POST['deleteImage']) && isset($_POST['imageID'])) {
		$imageID = get_post($conn, 'imageID');
	
		// deleting actual image
		$query = "SELECT imagePath from images WHERE imageID='$imageID'";
		$result = $conn->query($query);
		$rows = $result->num_rows;
		for ($j = 0 ; $j < $rows ; ++$j) {
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_NUM);
			if(unlink("$row[0]")) {
				//echo "Image deleted successfully";
			}
		}	
	
		// delete the data from database
		$query = "DELETE FROM images WHERE imageID='$imageID'";	
		$result = $conn->query($query);
		
		if (!$result) echo "DELETE failed: $query<br>" . $conn->error . "<br><br>";	
	}
}

  if(isset($_SESSION['customerID']) && isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $customerID = $_SESSION['customerID'];
    $credit = getUserInfo($conn, $customerID);
    echo '
    <div class="container-fluid bg-light">
    <h2>Overview </h2>
    <div class="container bg-light">
    <div class="row text-center">
    <div class="col-lg-3 col-md-6 mb-4">
    <div class="card">
    <div class="card-img-top">Profile</div>
    <div class="card-body">
    <p class="card-text">Fullname: '.$credit[0].'</p>
    <p class="card-text">Username: '.$credit[1].'</p>
    <p class="card-text">Email: '.$credit[2].'</p>
    <p class="card-text">Continent: '.$credit[3].'</p>
    <p class="card-text">Age: '.$credit[4].'</p>
    <p class="card-text">Credit: $'.$credit[5].'</p>
    </div>
  </div>
    </div>
</div>
    </div>
    <h2>Purchased Images </h2>
    <div class="container bg-light">
        <div class="row text-center">';
    //echo "'$customerID'";
    $query = "SELECT imageID, newPath, genre, price, title  FROM images where imageID in (select transactions.imageID from transactions WHERE customerID = '$customerID')";
    $result = $conn->query($query);
  
    if (!$result) die ("Database access failed: " . $conn->error);
  
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j) {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
      echo'
      <div class="col-lg-3 col-md-6 mb-4">
      <div class="card"><img class="card-img-top" src="'.$row[1].'" alt="">
            <div class="card-body">
          <h4 class="card-title">Title: '.$row[4].'</h4>
          <p class="card-text">Price: $'.$row[3].'</p>
          <p class="card-text">Genre: '.$row[2].'</p>
            </div>
            <div class="card-footer">
            <a href="photoedit.php?imageID='.$row[0].'" class="btn btn-secondary">Photo Edit Room</a>
      </div></div></div>';
    }   
    $result->close();
    
    echo'
        </div>
    </div>
    <h2>Uploaded Images </h2>
    <div class="container bg-light">
    <form action="myaccount.php" method="post">
    <!-- Page Features -->
    <div class="row text-center">';
    
    $query = "SELECT imageID, newPath, genre, price, title FROM images where customerID ='$customerID'";
    $result = $conn->query($query);
  
    if (!$result) die ("Database access failed: " . $conn->error);
  
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j) {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
      echo'
      <div class="col-lg-3 col-md-6 mb-4">
      <div class="card"><img class="card-img-top" src="'.$row[1].'" alt="">
            <div class="card-body">
          <h4 class="card-title">Title: '.$row[4].'</h4>
          <input type="hidden" name="deleteImage" value="yes">
          <input type="hidden" name="imageID" value="'.$row[0].'">
          <p class="card-text">Price: $'.$row[3].'</p>
          <p class="card-text">Genre: '.$row[2].'</p>
            </div>
            <div class="card-footer">
        <input type="submit" class="btn btn-secondary" value="Delete">
      </div></div></div>';
    }   
    $result->close();
    echo '
  </div>
</div>></form>
</div>';
  } else {
    header("Location: ../index.php?error=not_logged_in");
    $conn->close();
    exit();
}

?>