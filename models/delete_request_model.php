<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['user']) && !isset($_REQUEST['community']) && !isset($_REQUEST['requested_or_invited'])) {
    exit;
}

include('db_class.php'); // Include the database class 


if ($_REQUEST['requested_or_invited'] == 'requested') {
    // Get the variables and set them
    $user_id      = $_REQUEST['user'];
    $community_id = $_REQUEST['community'];
    
    $sql_delete_request = "DELETE FROM requests_to_join_communities WHERE user_id = '$user_id' AND community_id = '$community_id' AND requested_or_invited = '0'";
    
    if (mysqli_query($conn, $sql_delete_request)) {
        echo "success";
        exit();
    } else {
        echo "failed";
        exit();
    }
} else if ($_REQUEST['requested_or_invited'] == 'invited') {
	// Get the variables and set them
    $user_id      = $_REQUEST['user'];
    $community_id = $_REQUEST['community'];
    
    $sql_delete_request = "DELETE FROM requests_to_join_communities WHERE user_id = '$user_id' AND community_id = '$community_id' AND requested_or_invited = '1'";
    
    if (mysqli_query($conn, $sql_delete_request)) {
        echo "success";
        exit();
    } else {
        echo "failed";
        exit();
    }
} else {
	echo "failed";
	exit();
}

?>