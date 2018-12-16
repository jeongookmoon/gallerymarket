<?php
/**
 * Image Upload
 * 
 * @author Jeong Ook Moon
 */
?>

<?php
  require "navbar.php";
  require_once "image.php";
  define("IMGDIR", 'imgdata/');
?>
	<main>
		<div>		
			<p></p>
		</div>
		<div class="container-fluid bg-light">
			<?php
				if(isset($_SESSION['customerID']) && isset($_SESSION['username'])){
                    uploadImageForm();
                    uploadImage($conn);
				} else {
                    header("Location: ../index.php?error=not_logged_in");
                    exit();
				}
			?>
		</div>
	</main>

<?php
function uploadImageForm() {
echo <<< _END
<section class="container">
<div class="container-page">		
    <div class="col-md-6">
_END;
    if(isset($_GET['result'])=="success"){
    echo '<h3 class="text-success"> Uploaded successfully</p>';}
    echo <<< _END
        <h3 class="dark-grey">Upload Image</h3>

        <form enctype="multipart/form-data" action="upload.php" method="POST">
        
        <div class="form-group col-lg-6">
            <label>Select a file</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="12000000">
            <input name="image" type="file" class="btn btn-secondary"> 
        </div>

        <div class="form-group col-lg-6">
        <label>Title </label>
        <input type="text" name="title" >
        </div>
    
        <div class="form-group col-lg-6">
            <label>Genre</label>
            <select id="genre" name="genre">
            <option value="abstract">Abstract</option>
            <option value="modernart">ModernArt</option>
            <option value="impressionist">Impressionist</option>
            <option value="popart">PopArt</option>
            <option value="cubism">Cubism</option>
            <option value="contemporary">Contemporary</option>
            <option value="fantasy">Fantasy</option>
            <option value="graffiti">Graffiti</option>
            <option value="photo">Photo</option>
            </select>
        </div>

        <div class="form-group col-lg-6">
            <label>Price (USD)</label>
            <input type="text" name="price" id="price">
        </div>

        <div class="form-group col-lg-6">
            <label>For Free?</label>
            <input type="checkbox" name="forfree" id="forfree" value="1" onclick="disablePrice()">
        </div>

        <script>  
    
        function disablePrice(){  
             if(document.getElementById("forfree").checked == true){  
                 document.getElementById("price").disabled = true;  
             }else{
               document.getElementById("price").disabled = false;
             }  
        }  
        </script> 

        <div class="form-group col-lg-6">
        <input type="submit" name="uploadImage" class="btn btn-secondary">
        </div>

        </form>
    </div>
</div></section>
_END;
}

function uploadImage($rconn) {
	$conn = $rconn;
	// upload image
	if (isset($_POST['uploadImage']) && isset($_POST['genre']))  {
		// get image from local
		$image_name = $_FILES['image']['name'];
		$imagePath = IMGDIR . basename($image_name);

		// upload the image, source from php manual
		if(move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {	
            $forfree = 0;

            if(empty($_POST['forfree'])){
                $parts =  explode( ".", $image_name);
                $filetype = strtolower($parts[1]);
                $filename = strtolower($parts[0]);
    
                $newname = hs_name($filename);
                $newname = substr(md5($newname), 0, 10);
                $newname_compl = $newname . "." . $filetype;
                $newPath = IMGDIR . basename($newname_compl);
                
                copy($imagePath,  $newPath);
                
                $image1 = new Image('img/watermark.png');
                $image2 = new Image($imagePath);
                $image2->comp($image1, 0, 0, $imagePath, UNDER);
                $price = get_post($conn, 'price');
            } else {
                $forfree = 1;
                $newPath = $imagePath;
                $price = 0;
            }
            
            $genre = get_post($conn, 'genre');
			
			list( $width, $height ) = getimagesize( $imagePath );
			$resolution = $width . " x " . $height;
				
			$size = filesize($imagePath);
			$customerID = $_SESSION['customerID'];
            
            $title = get_post($conn, 'title');

		
			$query = "INSERT INTO images VALUES('', '$imagePath', '$genre', '$resolution', '$size', '$customerID', '$price', '$newPath', '$title', '$forfree')";
			$result = $conn->query($query);

			if (!$result) {
                echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
            } else {
                echo "Uploaded successfully!";
            }
		} else {
			echo "Failed to upload image\n";
		}
	}
}

function hs_name($var) {
    return hash('ripemd128', '@#H!0'.$var.'*@a');
}
?>
















