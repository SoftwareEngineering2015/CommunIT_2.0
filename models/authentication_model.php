<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['userid']) && !isset($_REQUEST['token'])) {
	exit;
}

include('db_class.php');

// Get the variables and set them
$userid = $_REQUEST['userid'];
$token = $_REQUEST['token'];

// MySQL sanitize
$userid = stripslashes($userid);
$token = stripslashes($token);

$userid = mysql_real_escape_string($userid);
$token = mysql_real_escape_string($token);

// Check to see if the username already exists
$sql_token_check = "SELECT `token`, `first_name`, `last_name` FROM users WHERE user_id='$userid'";
$result_token_check = mysqli_query($conn, $sql_token_check);
mysqli_close($conn);

if(mysqli_num_rows($result_token_check) > 0) {
	//echo "correct";
	while($row = mysqli_fetch_assoc($result_token_check)) {
		if ($token == $row['token'] && $row['token'] != null){
			$json_tokencheck_array = array(
				"success" => "Token match successful.",
				"firstname" => $row['first_name'],
				"lastname" => $row['last_name']
			);
		}else if($token != null){
			$json_tokencheck_array = array(
				"inactivity" => "Logged out due to inactivity, please log in.",
			);
		}else{
			$json_tokencheck_array = array(
				"error" => "No token found.",
			);
		}
		header('content-type: application/json');
		echo json_encode($json_tokencheck_array, JSON_PRETTY_PRINT);
		exit;
		}
}else{

	$json_tokencheck_array = array(
		"error" => "Logged out due to inactivity, please log in.",
	);
		header('content-type: application/json');
		echo json_encode($json_tokencheck_array, JSON_PRETTY_PRINT);
		exit;

}
?>
