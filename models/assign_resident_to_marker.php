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

$sql_check_if_profile_exists = "SELECT * FROM profiles WHERE profile_id = '$profile_id' LIMIT 1";
$sql_check_if_profile_exists_result = mysqli_query($conn, $sql_check_if_profile_exists);

while($row = $sql_check_if_profile_exists_result->fetch_assoc()){
    if ($row['phone_01'] != "" || $row['phone_02'] != "" || $row['email_01'] != "" || $row['email_02'] != "") {
    	$sql_assign_resident_to_marker = "INSERT INTO profiles_to_markers (marker_id, profile_id) VALUES ('$marker_id', '$profile_id')";
    	if(mysqli_query($conn, $sql_assign_resident_to_marker)) {
		    $sql_get_user_information = "SELECT profile_id, first_name, last_name FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profile_id = '$profile_id' LIMIT 1";

			$sql_get_user_information_result = mysqli_query($conn, $sql_get_user_information);
			while($row = $sql_get_user_information_result -> fetch_assoc()) {
				$json_return_array =  array(
				    "status" =>  "success",
				    "profile" =>  $row['profile_id'],
				    "first_name" => $row['first_name'],
				    "last_name" => $row['last_name']
				);
			}
			echo json_encode($json_return_array); exit();
		} else {
		    $json_return_array =  array(
		        "status" =>  "error"
		    );
		    echo json_encode($json_return_array); exit();
		}
    } else {
    	$sql_assign_resident_to_marker = "INSERT INTO profiles_to_markers (marker_id, profile_id) VALUES ('$marker_id', '$profile_id')";
    	if(mysqli_query($conn, $sql_assign_resident_to_marker)) {
		    $sql_get_user_information = "SELECT profile_id, first_name, last_name FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profile_id = '$profile_id' LIMIT 1";

			$sql_get_user_information_result = mysqli_query($conn, $sql_get_user_information);
			while($row = $sql_get_user_information_result -> fetch_assoc()) {
				$json_return_array =  array(
				    "status" =>  "success",
				    "profile" =>  $row['profile_id'],
				    "first_name" => $row['first_name'],
				    "last_name" => $row['last_name']
				);
			}
			echo json_encode($json_return_array); exit();
		} else {
		    $json_return_array =  array(
		        "status" =>  "error"
		    );
		    echo json_encode($json_return_array); exit();
		}
    }
}