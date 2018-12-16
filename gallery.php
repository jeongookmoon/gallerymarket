<?php
  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 
  require 'navbar.php';
  $sql = "SELECT * FROM images;";
  $result = mysqli_query($conn, $sql) or die("Bad query: $sql");
  $i = 0;

if(isset($_SESSION['customerID']) && isset($_SESSION['username'])){
    echo <<<_END
    <main>
    <div><p> </p></div>
    <div class="container-fluid bg-light">
        <form class="d-flex justify-content-center" action="gallery.php" method="get">
            <button class="btn btn-secondary mr-2 my-sm-1" type="submit">All</button>
_END;
            buttons($conn);
    echo <<<_END
        </form>
    </div>  
    <div class="container-fluid bg-light">		
_END;

    imageGalleryFormAll($conn);
    $conn->close();
    echo "</div></main>";
} else {
    header("Location: ../index.php?error=not_logged_in");
    $conn->close();
    exit();
}

function imageGalleryFormAll($rconn) {
    $conn = $rconn;
    if(isset($_GET['username'])){
        //echo $_GET['username'];
        $user = $_GET['username'];
        $query = "SELECT * FROM (SELECT customerID, imageID, imagePath, genre, price, username FROM images NATURAL JOIN customers where forfree = 1) A1 where A1.username = '$user'";
    } else {
        $query = "SELECT customerID, imageID, imagePath, genre, price, username FROM images NATURAL JOIN customers where forfree = 1";
    }
	
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	
	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);

	//  the following form was built based on referencing the lecture note
    //ImageID : $row[0], ImagePath : $row[1], Genre : $row[2], Resolution : $row[3] px, Size : $row[4] bytes, customerID : $row[5], Price : $$row[6]
    echo '<td class="gallery">
    <a href="'.$row[2].'" data-lightbox="gallery" data-title="'.$row[5].'" data-title="'.$row[5].'">
        <img src="'.$row[2].'"width="300" height="300" alt="'.$row[5].'"></a>
    </td>';
	}
$result->close();
}

function imageGalleryForm($rconn, $genre) {
	$conn = $rconn;
	$query = "SELECT * FROM images where genre= '$genre'";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	
	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);

	//  the following form was built based on referencing the lecture note
    //ImageID : $row[0], ImagePath : $row[1], Genre : $row[2], Resolution : $row[3] px, Size : $row[4] bytes, customerID : $row[5], Price : $$row[6]
    echo '<td class="gallery">
    <a href="'.$row[1].'" data-lightbox="gallery" data-title="'.$row[2].'">
        <img src="'.$row[1].'"width="300" height="300" alt="'.$row[2].'"></a>
    </td>';
	}
$result->close();
}

function buttons($rconn) {
    $conn = $rconn;
    $query = "SELECT username FROM images NATURAL JOIN customers where forfree=1 GROUP BY username";
	//$query = "SELECT genre FROM images where forfree=1 GROUP BY genre";
    $result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo'<button class="btn btn-secondary mr-2 my-sm-1" type="submit" name="username" value="'.strtolower($row[0]).'">'.$row[0].'</button>';
    }
    $result->close();
}
?>

