<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['userid'])) {
	exit;
}

include('db_class.php');

// Get the variables and set them
$user_id = $_REQUEST['userid'];
$newToken = generate_token();

function generate_token() {
    //Length of Hex Code
    $tokenLength = 20;
    //Characters to use in Hex Code
    $tokenOptions = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $token = '';
    //Assigns the
    $optionsLength = (strlen($tokenOptions) - 1);
    //Loops through and randomly creates a hex string
    for ($i = 0; $i < $tokenLength; $i++) {
        $n = mt_rand(0, $optionsLength);
        $token = $token . $tokenOptions[$n];
    }
    return $token;
}

// MySQL sanitize
$user_id = stripslashes($user_id);


// Check to see if the user_id already exists
$sql_user_id_update = "UPDATE `users` SET `token` = '$newToken' WHERE `user_id` = '$user_id'";
$result_user_id_update = mysqli_query($conn, $sql_user_id_update);

			$jsonArr_user_id_token = array(
					"user_id" => $user_id,
					"user_token" => $newToken
			);

/*
			$jsonArr_user_id_token = array(
				"results" => array($user_id, $newToken)
			);
*/
header('content-type: application/json');
echo json_encode($jsonArr_user_id_token, JSON_PRETTY_PRINT);


exit;

?>
