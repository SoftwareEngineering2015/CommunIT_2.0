<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['user'])) {
    echo "noUser";
    exit;
}

include('db_class.php'); // Include the database class 

// Get the variables and set them
$user_id = $_REQUEST['user'];

// MySQL sanitize
$user_id = stripslashes($user_id);

$user_id = mysql_real_escape_string($user_id);

$requests_array = array();

$sql_get_user_community_requests = "SELECT * FROM requests_to_join_communities INNER JOIN communities ON requests_to_join_communities.community_id = communities.community_id INNER JOIN config ON requests_to_join_communities.community_id = config.community_id WHERE requests_to_join_communities.user_id = '$user_id' ORDER BY requests_to_join_communities.date_created";

$sql_get_user_community_requests_result = mysqli_query($conn, $sql_get_user_community_requests);

if (mysqli_num_rows($sql_get_user_community_requests_result) == 0) {
    print "noRequests";
    exit();
} else {
    
    $sql_get_user_community_requests_result = mysqli_query($conn, $sql_get_user_community_requests);
    
    $counter = 0;
    while($row = $sql_get_user_community_requests_result -> fetch_assoc()) {
        $requests_array[$counter]['community_id'] = $row['community_id'];
        $requests_array[$counter]['community_name'] = $row['community_name'];
        $requests_array[$counter]['community_description'] = $row['community_description'];
        $requests_array[$counter]['city'] = $row['city'];
        $requests_array[$counter]['state'] = $row['province'];
        $requests_array[$counter]['country'] = $row['country'];
        $requests_array[$counter]['date_created'] = $row['date_created'];
        $requests_array[$counter]['requested_or_invited'] = $row['requested_or_invited'];

        $counter = $counter + 1;
    }

    echo json_encode($requests_array); exit();
}

?>