<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['marker'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$marker_id = $_REQUEST['marker'];

$sql_delete_marker = "DELETE FROM markers WHERE marker_id = '$marker_id'";

$sql_delete_marker_from_floorplan = "DELETE FROM markers_to_floorplans WHERE marker_id = '$marker_id'";

$sql_delete_profile_to_marker = "DELETE FROM profiles_to_markers WHERE marker_id = '$marker_id'";

if(mysqli_query($conn, $sql_delete_marker) && mysqli_query($conn, $sql_delete_marker_from_floorplan) && mysqli_query($conn, $sql_delete_profile_to_marker)) {
    echo "success"; exit();
} else {
    echo "failed"; exit();
}
?>