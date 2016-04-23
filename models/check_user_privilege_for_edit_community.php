<?php

// Get the community id from the post for which community map to display
if(isset($_REQUEST["community"]) && isset($_REQUEST["user"])) {
  $community_id = $_REQUEST["community"];
  $user_id = $_REQUEST["user"];
} else {
  echo "noMatch";
  exit;
}

include("db_class.php");

$json_return_array = array();

$sql_edit_community_privilege = "SELECT * FROM users_to_communities WHERE user_id = '$user_id' AND community_id = '$community_id' AND privilege_id <= 3 LIMIT 1";
$sql_edit_community_privilege_result = mysqli_query($conn, $sql_edit_community_privilege);

if (mysqli_num_rows($sql_edit_community_privilege_result) == 0 ) {

	$json_return_array['status'] = 'noMatch';
	echo json_encode($json_return_array);
  	exit();
} 
else {
	while ($row = $sql_edit_community_privilege_result -> fetch_assoc()) {
		$json_return_array['status'] = 'match';
		$json_return_array['privilege'] = $row['privilege_id'];
	}
	echo json_encode($json_return_array);
  	exit();
}

?>