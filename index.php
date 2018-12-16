<?php
/**
 * Image Upload
 * 
 * @author Jeong Ook Moon
 */
?>

<?php
  require "navbar.php";
  //SELECT customerID, MAX(counted) FROM ( SELECT customerID, COUNT(*) AS counted FROM images GROUP BY customerID ) AS counts;
  function getImgSource($rconn){
	$conn = $rconn;
	$query = "select imagePath from images where customerID in (select customerID from (select customerID, max(counted) from (select customerID, count(*) as counted from images group by customerID) as r1) as r2) order by imageID desc";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	$info = [];
	for ($j = 0 ; $j < 3 ; ++$j) {
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
	$info[$j]= $row;
	}   
	$result->close();
	return $info;
}
function getContinent($rconn){
	$conn = $rconn;
	$query = "select continent, count(continent) as counted from customers group by continent;";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	$info = [];
	for ($j = 0 ; $j < $rows ; ++$j) {
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
	$info[$j]= $row;
	}   
	$result->close();
	return $info;
}
function getAgeGroup($rconn){
	$conn = $rconn;
	$query = "select age, count(age) as counted from customers group by age;";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	$info = [];
	for ($j = 0 ; $j < $rows ; ++$j) {
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
	$info[$j]= $row;
	}   
	$result->close();
	return $info;
}
function usernameTop($rconn){
	$conn = $rconn;
	$query = "select username, count(*) from images NATURAL JOIN customers group by customerID;";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	$info = [];
	for ($j = 0 ; $j < $rows ; ++$j) {
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
	$info[$j]= $row;
	}   
	$result->close();
	return $info;
}
function usernameTopSingle($rconn){
	$conn = $rconn;
	$query = "select username from (select username, count(*) as counted from images NATURAL JOIN customers group by customerID) as r2 having max(counted);";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	$info = "";
	for ($j = 0 ; $j < $rows ; ++$j) {
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
	$info = $row[0];
	}   
	$result->close();
	return $info;
}
function genreTop($rconn){
	$conn = $rconn;
	$query = "select genre, count(*) from images group by genre;";
	$result = $conn->query($query);

	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;
	$info = [];
	for ($j = 0 ; $j < $rows ; ++$j) {
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
	$info[$j]= $row;
	}   
	$result->close();
	return $info;
}
$ob = getImgSource($conn); 
$continent = getContinent($conn);
$age = getAgeGroup($conn);
$user = usernameTop($conn);
$genre = genreTop($conn);
$topuser = usernameTopSingle($conn);
//print_r($ob[0][0]);
?>
	<main>
		<div>		
			<p></p>
		</div>
		<div class="container-fluid bg-light">
			<?php
				if(isset($_SESSION['customerID']) && isset($_SESSION['username'])){
					echo '<div class="container-fluid bg-light">
							<div class="row">
							<div><h2>User Continent Data</h2></div><div>';
					for ($i =0; $i < count($continent); $i++) {
						echo'<ul>';
						for ($j =0; $j < count($continent[$i]); $j++) {
							echo " ".$continent[$i][$j];
						}
						echo '</ul>';
					} echo'</div></div>';
					echo'<div class="row"><div><h2>User Age Group Data</h2></div><div>';
					for ($i =0; $i < count($age); $i++) {
						echo'<ul>';
						for ($j =0; $j < count($age[$i]); $j++) {
							echo " ".$age[$i][$j];
						}
						echo '</ul>';
					} echo'</div></div>';
					echo'<div class="row"><div><h2>User Contribution Data</h2></div><div>';
					for ($i =0; $i < count($user); $i++) {
						echo'<ul>';
						for ($j =0; $j < count($user[$i]); $j++) {
							echo " ".$user[$i][$j];
						}
						echo '</ul>';
					} echo'</div></div>';
					echo'<div class="row"><div><h2>Genre Distribution Data</h2></div><div>';
					for ($i =0; $i < count($genre); $i++) {
						echo'<ul>';
						for ($j =0; $j < count($genre[$i]); $j++) {
							echo " ".$genre[$i][$j];
						}
						echo '</ul>';
					} echo'</div></div>';
				} else {
					echo '<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
							<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
							<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
							<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
							</ol>
							<div class="carousel-inner">
							<div class="carousel-item active">
							<img src="'.$ob[0][0].'" style="width:100%; height: 700px !important;" alt="First slide">
							<div class="carousel-caption d-none d-sm-block">
								<h5> Images by our top contributor, '.$topuser.'</h5>
							</div>
							</div>
							<div class="carousel-item">
							<img src="'.$ob[1][0].'" style="width:100%; height: 700px !important;" alt="Second slide">
							<div class="carousel-caption d-none d-sm-block">
								<h5> Images by our top contributor, '.$topuser.'</h5>
							</div>
							</div>
							<div class="carousel-item">
							<img src="'.$ob[2][0].'" style="width:100%; height: 700px !important;" alt="Third slide">
							<div class="carousel-caption d-none d-sm-block">
							<h5> Images by our top contributor, '.$topuser.'</h5>
							</div>
							</div>
							</div>
							<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
							</a>
						  </div>';
				}
			?>
		</div>
	</main>






















