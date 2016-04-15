<?php

if (!isset($_POST['floor']) || !isset($_POST['submit'])) {
    exit;
}

include('db_class.php');
$marker_id     = $_POST['submit'];
$floor         = $_POST['floor'];
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
    $json_return_array =  array(
        "status" =>  "error",
        "message" =>  "Sorry, only JPG, JPEG, PNG & GIF files are allowed."
    );
    echo json_encode($json_return_array); exit();
    $uploadOk = 0;
    exit;
}

//Checks to make sure the Marker ID is valid.
$sql_marker_id_check    = "SELECT marker_id FROM markers WHERE marker_id = '$marker_id' LIMIT 1";
$result_marker_id_check = mysqli_query($conn, $sql_marker_id_check);

if (mysqli_fetch_row($result_marker_id_check)) {
    
    $error_counter = 0;
    
    do {
        $available    = false;
        $floorplan_id = generate_HexCode();
        
        //Checks to make sure that the Floor Plan ID has not been taken.
        $sql_floorplan_id_check    = "SELECT floorplan_id FROM floor_plans WHERE floorplan_id= '$floorplan_id' LIMIT 1";
        $result_floorplan_id_check = mysqli_query($conn, $sql_floorplan_id_check);
        
        //Check to see if the User ID Already Exists
        if (mysqli_fetch_row($result_floorplan_id_check)) {
            $available = false;
            $error_counter++;
        } else {
            $available            = true;
            $image_location       = "images/floorplans/" . $floorplan_id . "." . $imageFileType;
            $sql_upload_floorplan = "INSERT INTO floor_plans (floorplan_id, floor, image_location) VALUES ('$floorplan_id', '$floor', '$image_location')";
            
            $sql_marker_floorplan = "INSERT INTO floorplans_to_markers (floorplan_id, marker_id) VALUES ('$floorplan_id', '$marker_id')";
            
            if (mysqli_query($conn, $sql_upload_floorplan) && mysqli_query($conn, $sql_marker_floorplan)) {
                mysqli_close($conn);
                if (uploadFloorPlan($floorplan_id) != false) {
                  uploadFloorPlan($floorplan_id);
                }
                $json_return_array =  array(
                    "status" =>  "success",
                    "floorplan_id" =>  $floorplan_id,
                    "floor" => $floor,
                );
                echo json_encode($json_return_array); exit();
            } else {
                $available = false;
            }            
        }
        if ($error_counter == 100) {
            $json_return_array =  array(
                    "status" =>  "error",
                    "message" =>  "Time out error occurred, please try again."
            );
            echo json_encode($json_return_array); exit();
        }
    } while ($available == false && $error_counter < 100);
    
    exit();
    
} else {
    $json_return_array =  array(
        "status" =>  "error",
        "message" =>  "Marker not found."
    );
    echo json_encode($json_return_array); exit();
}


function generate_HexCode()
{
    //Length of Hex Code
    $hexIDLength   = 12;
    //Characters to use in Hex Code
    $hexOptions    = 'ABCDEF1234567890';
    $hexID         = '';
    //Assigns the
    $optionsLength = (strlen($hexOptions) - 1);
    //Loops through and randomly creates a hex string
    for ($i = 0; $i < $hexIDLength; $i++) {
        $n     = mt_rand(0, $optionsLength);
        $hexID = $hexID . $hexOptions[$n];
    }
    return $hexID;
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
            $json_return_array =  array(
                "status" =>  "error",
                "message" =>  "File is not an image."
            );
            echo json_encode($json_return_array); exit();
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
        $json_return_array =  array(
                "status" =>  "error",
                "message" =>  "Sorry, your file was not uploaded."
            );
        echo json_encode($json_return_array); exit();
        return false;
        //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        //if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $target_file = $target_dir . "." . $imageFileType;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            $json_return_array =  array(
                "status" =>  "error",
                "message" =>  "Sorry, there was an error uploading your file."
            );
            echo json_encode($json_return_array); exit();
            return false;
            //echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
