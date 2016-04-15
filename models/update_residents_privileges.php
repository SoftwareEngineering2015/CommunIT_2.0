<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community']) && !isset($_REQUEST['users']) && !isset($_REQUEST['privileges'])) {
    exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];
$users = $_REQUEST['users'];
$privileges = $_REQUEST['privileges'];

$sanitized_user; // Holds the sanitized user id
$sanitized_privilege; // Holds the sanitized privilege


foreach ($users as $key => $value) {
	$sanitized_user = stripslashes($value); // Do this here because data passed to it is in an array
	$sanitized_privilege = stripslashes($privileges[$key]); // Do this here because data passed to it is in an array

	$sanitized_user = mysql_real_escape_string($sanitized_user); // Do this here because data passed to it is in an array
	$sanitized_privilege = mysql_real_escape_string($sanitized_privilege); // Do this here because data passed to it is in an array

	if ($sanitized_privilege === "owner") { 
		$sql_update_resident_privilege = "UPDATE users_to_communities SET  privilege_id='2' WHERE user_id= '$sanitized_user' AND community_id='$community_id'";
		if (mysqli_query($conn, $sql_update_resident_privilege)) {
			continue;
		} else {
			echo "failed"; exit();
		}
	} else if ($sanitized_privilege === "moderator") { 
		$sql_update_resident_privilege = "UPDATE users_to_communities SET  privilege_id='3' WHERE user_id= '$sanitized_user' AND community_id='$community_id'";
		if (mysqli_query($conn, $sql_update_resident_privilege)) {
			continue;
		} else {
			echo "failed"; exit();
		}
	} elseif ($sanitized_privilege === "resident") {
		$sql_update_resident_privilege = "UPDATE users_to_communities SET privilege_id='4' WHERE user_id= '$sanitized_user' AND community_id='$community_id'";
		if (mysqli_query($conn, $sql_update_resident_privilege)) {
			continue;
		} else {
			echo "failed"; exit();
		}
	} else {
		continue;
	}
	
}

echo "success"; exit();

?>