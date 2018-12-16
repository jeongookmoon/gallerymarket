<?php
 require 'photoedit_navbar.php';
 
 function getImgSource($rconn){
	$conn = $rconn;
	$imageID = $_GET['imageID'];
	$query = "SELECT imageID, newPath FROM images where imageID = $imageID";
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
<body class="w3-light-grey">
<div><p> </p></div>
    <div class="container-fluid bg-light">
<div class="w3-content w3-margin-top" style="max-width:1400px;">
		<div class="w3-row-padding">
				<div class="w3-col m2">
						<div class="w3-white w3-card-4">
								<div class="w3-container">
										<h3>Effects</h3>
										<div class="w3-bar-block" id="effects">
												<a href="#effect" data-effect="blur" class="w3-bar-item w3-button">Blur</a>
												<a href="#effect" data-effect="blur2" class="w3-bar-item w3-button">Blur (rad=4)</a>
												<a href="#effect" data-effect="sharpen" class="w3-bar-item w3-button">Sharpen</a>
												<a href="#effect" data-effect="lighten" class="w3-bar-item w3-button">Lighten</a>
												<a href="#effect" data-effect="darken" class="w3-bar-item w3-button">Darken</a>
												<a href="#effect" data-effect="emboss" class="w3-bar-item w3-button">Emboss</a>
												<a href="#effect" data-effect="edge-enhance" class="w3-bar-item w3-button">Edge enhance</a>
												<a href="#effect" data-effect="edge-detect" class="w3-bar-item w3-button">Edge detect</a>
												<a href="#effect" data-effect="hard-edge" class="w3-bar-item w3-button">Hard edge</a>
												<a href="#effect" data-effect="laplace" class="w3-bar-item w3-button">Laplace</a>
										</div>
										<br/>
								</div>
						</div>
				</div>
				<div class="w3-col m8 w3-center">
						<div class="w3-container">
							<?php
								$imageSource = getImgSource($conn);
								echo '<img id="imgs" src="'.$imageSource[1].'" class="img-thumbnail"></img>';
							?>
						</div>
				</div>
				<div class="w3-col m2">
						<div class="w3-white w3-card-4">
								<div class="w3-container">
										<h3>Filters</h3>
										<div class="w3-bar-block" id="filters">
												<a href="#filter" data-effect="b&w" class="w3-bar-item w3-button">B&amp;W</a>
												<a href="#filter" data-effect="sepia" class="w3-bar-item w3-button">Sepia</a>
												<a href="#filter" data-effect="vintage" class="w3-bar-item w3-button">Vintage</a>
												<a href="#filter" data-effect="red" class="w3-bar-item w3-button">Red</a>
												<a href="#filter" data-effect="blue" class="w3-bar-item w3-button">Blue</a>
												<a href="#filter" data-effect="green" class="w3-bar-item w3-button">Green</a>
												<a href="#filter" data-effect="recolor" class="w3-bar-item w3-button">Recolor</a>
										</div>
										<br/>
										<button onclick="reloadFunction()" class="btn btn-outline-secondary">Reload</button>

<script>
function reloadFunction() {
    location.reload();
}
</script>
								</div>
						</div>
				</div>
		</div>
</div>
</div>

<!-- below source is from https://github.com/aurbano/photojshop and https://kautube.com/image-editor-effect-filter-with-jquery/ -->
<script src="js/jquery.js"></script>
<script src="js/photojshop.jquery.js"></script>
<script src="js/script.js"></script>
</body>