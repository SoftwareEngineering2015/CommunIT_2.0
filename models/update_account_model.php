<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['user'])) {
	exit;
}

include('db_class.php');

// Get the variables and set them
$user_id = $_REQUEST['user'];
$user_id = stripslashes($user_id);
$user_id = mysql_real_escape_string($user_id);


// MySQL sanitize
if( isset($_REQUEST['email']) ) {
	$email = $_REQUEST['email'];
	$email = stripslashes($email);
	$email = mysql_real_escape_string($email);
}else {
	$email = null;
}

if( isset($_REQUEST['password']) ) {
	$password = $_REQUEST['password'];
	$password = stripslashes($password);
	$password = mysql_real_escape_string($password);
}else {
	$password = null;
}

// Check to see if the username already exists
$sql_userid_check = "SELECT `user_id` FROM users WHERE user_id='$user_id' LIMIT 1";
$result_userid_check = mysqli_query($conn, $sql_userid_check);

if(mysqli_num_rows($result_userid_check) > 0) {

	if ($password != null) {
			//echo ("Made it here: password");
			$query = "UPDATE users SET password = '$password' WHERE user_id = '$user_id'";
			$result =  mysqli_query($conn, $query);

			$json_update_array = array(
					"success" => "Account Password Update Successful"
			);
	};


	if ($email != null) {
			//echo ("Made it here: email");
			$query = "UPDATE `users` SET `email` = '$email' WHERE `user_id` = '$user_id'";
			$result = mysqli_query($conn, $query);

			$json_update_array = array(
					"success" => "Account E-mail Update Successful"
			);
	};

	if ($email != null && $password != null){

		$json_update_array = array(
				"success" => "Account Password and E-mail Update Successful"
		);

	};

		header('content-type: application/json');
		echo json_encode($json_update_array, JSON_PRETTY_PRINT);
		exit;

	} else {
		$json_update_array = array(
				"error" => "User ID doesn't exist."
		);

	header('content-type: application/json');
	echo json_encode($json_update_array, JSON_PRETTY_PRINT);
	exit;
}


?>
