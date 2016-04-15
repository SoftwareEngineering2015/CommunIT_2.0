<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['marker']) && !isset($_REQUEST['inputMarkerName']) && !isset($_REQUEST['inputMarkerLocation']) && !isset($_REQUEST['inputPinColor']) && !isset($_REQUEST['inputMarkerLatitude']) && !isset($_REQUEST['inputMarkerLongitude'])) {
    exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$marker_id = $_REQUEST['marker'];
$name = $_REQUEST['inputMarkerName'];
$location = $_REQUEST['inputMarkerLocation'];
$pin_color = $_REQUEST['inputPinColor'];
$latitude = $_REQUEST['inputMarkerLatitude'];
$longitude = $_REQUEST['inputMarkerLongitude'];

// MySQL sanitize
$name = stripslashes($name);
$location = stripslashes($location);
$pin_color = stripslashes($pin_color);
$latitude = stripslashes($latitude);
$longitude = stripslashes($longitude);

$name = mysql_real_escape_string($name);
$location = mysql_real_escape_string($location);
$pin_color = mysql_real_escape_string($pin_color);
$latitude = mysql_real_escape_string($latitude);
$longitude = mysql_real_escape_string($longitude);


$sql_update_marker_name = "UPDATE markers SET  name='$name' WHERE marker_id= '$marker_id'";

$sql_update_marker_location = "UPDATE markers SET  location='$location' WHERE marker_id= '$marker_id'";

$sql_update_marker_pin_color = "UPDATE markers SET  pin_color='$pin_color' WHERE marker_id= '$marker_id'";

$sql_update_marker_latitude = "UPDATE markers SET  latitude='$latitude' WHERE marker_id= '$marker_id'";

$sql_update_marker_longitude = "UPDATE markers SET  longitude='$longitude' WHERE marker_id= '$marker_id'";

if (mysqli_query($conn, $sql_update_marker_name) && mysqli_query($conn, $sql_update_marker_location) && mysqli_query($conn, $sql_update_marker_pin_color) && mysqli_query($conn, $sql_update_marker_latitude) && mysqli_query($conn, $sql_update_marker_longitude)) {

    $json_return_array =  array(
        "status" =>  "success",
        "marker_id" =>  $marker_id,
        "marker_name" => $name,
        "marker_location" => $location,
    );
    echo json_encode($json_return_array); exit();
} else {
    $json_return_array =  array(
    	"status" =>  "error",
    );
    echo json_encode($json_return_array); exit();
}
?>