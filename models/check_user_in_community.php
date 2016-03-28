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

$sql_is_user_in_community = "SELECT * FROM users_to_communities WHERE user_id = '$user_id' AND community_id = '$community_id'";
$sql_is_user_in_community_result = mysqli_query($conn, $sql_is_user_in_community);

if (mysqli_num_rows($sql_is_user_in_community_result) == 0 ) {
  echo "noMatch"; exit();
} 
else {
  echo "match"; exit();
}

?>