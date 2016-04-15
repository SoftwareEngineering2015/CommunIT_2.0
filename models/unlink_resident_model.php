<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['marker']) && !isset($_REQUEST['profile'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

$json_return_array = array();
// Get the variables and set them
$marker_id = $_REQUEST['marker'];
$profile_id = $_REQUEST['profile'];

$sql_delete_profile_to_marker = "DELETE FROM profiles_to_markers WHERE marker_id = '$marker_id' AND profile_id = '$profile_id'";

if(mysqli_query($conn, $sql_delete_profile_to_marker)) {
	$sql_get_user_information = "SELECT profile_id, first_name, last_name FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profile_id = '$profile_id' LIMIT 1";

	$sql_get_user_information_result = mysqli_query($conn, $sql_get_user_information);
	while($row = $sql_get_user_information_result -> fetch_assoc()) {
		$json_return_array =  array(
		    "status" =>  "success",
		    "profile" =>  $row['profile_id'],
		    "name" => $row['first_name'] . " " . $row['last_name']
		);
	}
	echo json_encode($json_return_array); exit();
} else {
    $json_return_array =  array(
        "status" =>  "error"
    );
    echo json_encode($json_return_array); exit();
}
?>