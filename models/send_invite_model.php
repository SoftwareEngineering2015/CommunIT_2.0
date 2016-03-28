<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community']) && !isset($_REQUEST['user'])) {
    echo "noCommunity";
    exit;
}

include('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];
$username     = $_REQUEST['user'];

// MySQL sanitize
$community_id = stripslashes($community_id);
$username     = stripslashes($username);

$community_id = mysql_real_escape_string($community_id);
$username     = mysql_real_escape_string($username);

$sql_check_if_user_exists = "SELECT * FROM users WHERE username = '$username' LIMIT 1";

$sql_check_if_user_exists_result = mysqli_query($conn, $sql_check_if_user_exists);

if (mysqli_num_rows($sql_check_if_user_exists_result) < 1) {
    print "noUser";
    exit();
} else {
    $sql_check_if_user_exists_result = mysqli_query($conn, $sql_check_if_user_exists);
    
    while ($row = $sql_check_if_user_exists_result->fetch_assoc()) {
        $user_id = $row['user_id'];

        $sql_check_if_user_is_in_community = "SELECT * FROM users_to_communities WHERE community_id = '$community_id' AND user_id = '$user_id' LIMIT 1";
        
        $sql_check_if_user_is_in_community_result = mysqli_query($conn, $sql_check_if_user_is_in_community);
        
        if (mysqli_num_rows($sql_check_if_user_is_in_community_result) > 0) {
            print "alreadyJoined";
            exit();
        } else {
            $sql_check_for_invite = "SELECT * FROM requests_to_join_communities WHERE community_id = '$community_id' AND user_id = '$user_id' LIMIT 1";
            
            $sql_check_for_invite_result = mysqli_query($conn, $sql_check_for_invite);
            
            if (mysqli_num_rows($sql_check_for_invite_result) > 0) {
                print "alreadyInvited";
                exit();
            } else {
                $sql_invite_to_join_community = "INSERT INTO requests_to_join_communities (user_id, community_id, requested_or_invited, date_created) VALUES ('$user_id', '$community_id', '1', NOW())";
                
                if (mysqli_query($conn, $sql_invite_to_join_community)) {
                    echo "success";
                    exit();
                } else {
                    echo "fail";
                    exit();
                }
            }
            
        }
    }
    
}

?>