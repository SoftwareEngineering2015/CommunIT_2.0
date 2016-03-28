<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['user']) && !isset($_REQUEST['community']) ) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$user_id = $_REQUEST['user'];
$community_id = $_REQUEST['community'];

$sql_delete_request_invite = "DELETE FROM requests_to_join_communities WHERE user_id = '$user_id' AND community_id = '$community_id'";

if(mysqli_query($conn, $sql_delete_request_invite)) {
    echo "success"; exit();
} else {
    echo "failed"; exit();
}
?>