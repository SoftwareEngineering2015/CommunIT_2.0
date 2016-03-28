<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community']) && !isset($_REQUEST['user'])) {
    echo "noCommunity";
    exit;
}

include('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];
$user_id      = $_REQUEST['user'];

// MySQL sanitize
$community_id = stripslashes($community_id);
$user_id      = stripslashes($user_id);

$community_id = mysql_real_escape_string($community_id);
$user_id      = mysql_real_escape_string($user_id);

$sql_check_if_user_in_community = "SELECT * FROM users_to_communities WHERE community_id = '$community_id' AND user_id = '$user_id' LIMIT 1";

$sql_check_if_user_in_community_result = mysqli_query($conn, $sql_check_if_user_in_community);

if (mysqli_num_rows($sql_check_if_user_in_community_result) == 1) {
    print "alreadyJoined";
    exit();
} else {
    $sql_check_if_user_requested = "SELECT * FROM requests_to_join_communities WHERE community_id = '$community_id' AND user_id = '$user_id' LIMIT 1";
    
    $sql_check_if_user_requested_result = mysqli_query($conn, $sql_check_if_user_requested);
    
    if (mysqli_num_rows($sql_check_if_user_requested_result) == 1) {
        print "alreadyRequested";
        exit();
    } else {
        $sql_request_to_join_community = "INSERT INTO requests_to_join_communities (user_id, community_id, requested_or_invited, date_created) VALUES ('$user_id', '$community_id', '0', NOW())";
        
        if (mysqli_query($conn, $sql_request_to_join_community)) {
            echo "success"; exit();
        } else {
            echo "fail"; exit();
        }
        
    }
    
}

?>