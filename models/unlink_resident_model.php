<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['marker']) && !isset($_REQUEST['profile'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$marker_id = $_REQUEST['marker'];
$profile_id = $_REQUEST['profile'];

$sql_delete_profile_to_marker = "DELETE FROM profiles_to_markers WHERE marker_id = '$marker_id' AND profile_id = '$profile_id'";

if(mysqli_query($conn, $sql_delete_profile_to_marker)) {
    echo "success"; exit();
} else {
    echo "failed"; exit();
}
?>