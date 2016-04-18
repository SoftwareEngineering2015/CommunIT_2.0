<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['username']) && !isset($_REQUEST['password'])) {
	exit;
}

include('db_class.php');

// Get the variables and set them
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

// MySQL sanitize
$username = stripslashes($username);
$password = stripslashes($password);

$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);

// Check to see if the username already exists
$sql_username_check = "SELECT `user_id`, `token`, `password` FROM users WHERE username='$username' LIMIT 1";
$result_username_check = mysqli_query($conn, $sql_username_check);


if(mysqli_num_rows($result_username_check) > 0) {
	//echo "correct";
	while($row = mysqli_fetch_assoc($result_username_check)) {
	  $hash = $row['password'];
	  if (password_verify($password, $hash)) {
	    $json_login_array =  array(
	    	"user_id" => $row['user_id'],
	    	"token" => $row['token']
		);
	  } else {
	    $json_login_array = array(
			"error" => "Incorrect Login"
		);
	  }

	}

	header('content-type: application/json');
	echo json_encode($json_login_array, JSON_PRETTY_PRINT);
	exit;

} else {
	$json_login_array = array(
			"error" => "Incorrect Login"
	);

	/*
	$jsonArr_user_id_token = array(
		"results" => array($user_id, $newToken)
	);
	*/
	header('content-type: application/json');
	echo json_encode($json_login_array, JSON_PRETTY_PRINT);
	exit;
}


?>
