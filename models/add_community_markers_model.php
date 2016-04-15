<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community']) && !isset($_REQUEST['inputMarkerName']) && !isset($_REQUEST['inputMarkerLocation']) && !isset($_REQUEST['inputPinColor']) && !isset($_REQUEST['inputMarkerHasFloorplans']) && !isset($_REQUEST['inputMarkerLatitude']) && !isset($_REQUEST['inputMarkerLongitude'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];
$name = $_REQUEST['inputMarkerName'];
$location = $_REQUEST['inputMarkerLocation'];
$pin_color = $_REQUEST['inputPinColor'];
$has_floorplans = $_REQUEST['inputMarkerHasFloorplans'];
$latitude = $_REQUEST['inputMarkerLatitude'];
$longitude = $_REQUEST['inputMarkerLongitude'];

// MySQL sanitize
$name = stripslashes($name);
$location = stripslashes($location);
$pin_color = stripslashes($pin_color);
$has_floorplans = stripslashes($has_floorplans);
$latitude = stripslashes($latitude);
$longitude = stripslashes($longitude);

$name = mysql_real_escape_string($name);
$location = mysql_real_escape_string($location);
$pin_color = mysql_real_escape_string($pin_color);
$has_floorplans = mysql_real_escape_string($has_floorplans);
$latitude = mysql_real_escape_string($latitude);
$longitude = mysql_real_escape_string($longitude);

$json_return_array = array();

// Loop that interacts with the database to insert the user
$error_counter = 0;
do {
    $available = true;
    $marker_id = generate_HexCode();
    
    $sql_marker_id_check = "SELECT marker_id FROM markers WHERE marker_id= '$marker_id' LIMIT 1";
    $result_marker_id_check = mysqli_query($conn, $sql_marker_id_check); 

    //Check to see if the User ID Already Exists
    if(mysqli_fetch_row($result_marker_id_check)){
            $available = false;
            $error_counter++;
        }else{
            $available = true;
            $sql_create_marker = "INSERT INTO markers (marker_id, name, latitude, longitude, location, pin_color, has_floorplan) VALUES ('$marker_id', '$name', '$latitude', '$longitude', '$location', '$pin_color', '$has_floorplans')";
            $sql_markers_to_communities = "INSERT INTO markers_to_communities (community_id, marker_id) VALUES ('$community_id', '$marker_id')";
            if(mysqli_query($conn, $sql_create_marker) && mysqli_query($conn, $sql_markers_to_communities)) {
                $json_return_array =  array(
                    "status" =>  "success",
                    "marker_id" =>  $marker_id,
                    "marker_name" => $name,
                    "marker_location" => $location,
                    "has_floorplan" => $has_floorplans
                );
                echo json_encode($json_return_array); exit();
            } 
            // Close the connection down here somewhere
        }
    if($error_counter == 100){
        $json_return_array =  array(
            "status" =>  "error",
        );
        echo json_encode($json_return_array); exit();
    }
}while($available == false && $error_counter < 100);

//Function to generate Hex Code
function generate_HexCode() {
    //Length of Hex Code
    $hexIDLength = 12;
    //Characters to use in Hex Code
    $hexOptions = 'ABCDEF1234567890';
    $hexID = ''; 
    //Assigns the 
    $optionsLength = (strlen($hexOptions) - 1);
    //Loops through and randomly creates a hex string
    for ($i = 0; $i < $hexIDLength; $i++) {
        $n = mt_rand(0, $optionsLength);
        $hexID = $hexID . $hexOptions[$n];
    }
    return $hexID;
}

?>