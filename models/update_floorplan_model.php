<?php

if (isset($_REQUEST['floor'])) {
    include('db_class.php'); // Include the database class 
    
    // Get the variables and set them
    $floorplan_id = $_REQUEST['floorplan'];
    $floor        = $_REQUEST['floor'];
    
    // MySQL sanitize
    $floor = stripslashes($floor);
    
    $floor = mysql_real_escape_string($floor);
    
    
    $sql_update_floorplan_name = "UPDATE floor_plans SET  floor='$floor' WHERE floorplan_id= '$floorplan_id'";
    
    if (mysqli_query($conn, $sql_update_floorplan_name)) {
        
        echo "success";
        exit();
        //header( 'Location: myhome.php' );
    } else {
        echo "fail";
        exit();
    }
} else if (isset($_FILES["fileToUpload"]) && isset($_POST['submit'])) {
    include('db_class.php');
    $floorplan_id     = $_POST['submit'];
    $target_dir    = "../images/floorplans/file";
    $target_file   = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
        return false;
        
    }
    
    if ($_FILES["fileToUpload"]["size"] > 1024000) {
        //echo "Sorry, your file is too large.";
        //echo $_FILES["fileToUpload"]["size"];
        $uploadOk = 0;
        return false;
    }
    
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF") {
    	$jsonArr_floorplans = array(
            "status" => "Error",
            "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."
        );
        echo json_encode($jsonArr_floorplans);
        $uploadOk = 0;
        exit;
    }
    
    $sql_get_image = "SELECT image_location FROM floor_plans WHERE floorplan_id = '$floorplan_id'";
	$sql_get_image_result = mysqli_query($conn, $sql_get_image);

	while ($row = $sql_get_image_result->fetch_assoc()) {
  		$image = "../". $row['image_location'];
	}

    $image_location       = "images/floorplans/" . $floorplan_id . "." . $imageFileType;
    $sql_update_floorplan_image = "UPDATE floor_plans SET image_location = '$image_location' WHERE floorplan_id = '$floorplan_id'";
    
    if (mysqli_query($conn, $sql_update_floorplan_image) && unlink($image)) {
    	uploadFloorPlan($floorplan_id);
        $jsonArr_floorplans = array(
            "status" => "success",
            "message" => $image_location
        );
        echo json_encode($jsonArr_floorplans);
        mysqli_close($conn);
        exit();
    } else {
        $jsonArr_floorplans = array(
            "status" => "Error",
            "message" => "Error updating the floorplan image."
        );
        echo json_encode($jsonArr_floorplans);
        exit();
    }
} else {
    exit();
}

function uploadFloorPlan($floorplan_id)
    {
        $target_dir      = "../images/floorplans/" . $floorplan_id;
        $target_file_ext = basename($_FILES["fileToUpload"]["name"]);
        $target_file     = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk        = 1;
        $imageFileType   = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
            	$jsonArr_floorplans = array(
            		"status" => "Error",
            		"message" => "File is not an image."
        		);
        		echo json_encode($jsonArr_floorplans);
                $uploadOk = 0;
                return false;
                
            }
        }
        /*
        // Check if file already exists
        /*
        if (file_exists($target_file)) {
        $json_floorplan_check_array = array(
        "error" => "Sorry, file already exists.",
        );
        //echo "Sorry, file already exists.";
        //echo file_exists($target_file);
        $uploadOk = 0;
        return false;
        }
        */
        // Check file size
        /*
        if ($_FILES["fileToUpload"]["size"] > 1024000) {
        //echo "Sorry, your file is too large.";
        //echo $_FILES["fileToUpload"]["size"];
        $uploadOk = 0;
        return false;
        }
        */
        // Allow certain file formats
        /*
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $json_floorplan_check_array = array(
        "error" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed.",
        );
        $uploadOk = 0;
        return false;
        }
        */
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        	$jsonArr_floorplans = array(
            		"status" => "Error",
            		"message" => "Sorry, your file was not uploaded."
        		);
        	echo json_encode($jsonArr_floorplans);
            return false;
            //echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            //if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $target_file = $target_dir . "." . $imageFileType;
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                $jsonArr_floorplans = array(
            		"status" => "Error",
            		"message" => "Sorry, your file was not uploaded."
        		);
        		echo json_encode($jsonArr_floorplans);
                return false;
                //echo "Sorry, there was an error uploading your file.";
            }
        }
    }

?>