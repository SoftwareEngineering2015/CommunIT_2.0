<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community']) && !isset($_REQUEST['user'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];
$user_id = $_REQUEST['user'];

$sql_leave_community = "DELETE FROM users_to_communities WHERE community_id = '$community_id' AND user_id='$user_id'";

$sql_delete_profile_in_community = "DELETE FROM profiles WHERE community_id = '$community_id' AND user_id='$user_id'";

if(mysqli_query($conn, $sql_leave_community) && mysqli_query($conn, $sql_delete_profile_in_community)) {
	echo "success"; exit();
} else {
	echo "failed"; exit();
}
?>