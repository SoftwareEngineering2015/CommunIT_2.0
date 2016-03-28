<?php

// Get the community id from the post for which community map to display
if(isset($_REQUEST["user"])) {
  $user_id = $_REQUEST["user"];
} else {
  echo "noUser";
  exit;
}

include("db_class.php");

// This multidimensional array holds the information of the communities the user is in
$users_to_communities_array = array();

$sql_get_user_communities = "SELECT * FROM communities INNER JOIN config ON communities.community_id = config.community_id INNER JOIN users_to_communities ON communities.community_id = users_to_communities.community_id INNER JOIN privileges ON users_to_communities.privilege_id = privileges.privilege_id WHERE users_to_communities.user_id = '$user_id'";
$sql_get_user_communities_result = mysqli_query($conn, $sql_get_user_communities);


$counter = 0; // Counter for the array pointer


if (mysqli_num_rows($sql_get_user_communities_result) == 0 ) {
  print "noCommunities"; exit();
} else {
  $sql_get_user_communities_result = mysqli_query($conn, $sql_get_user_communities);
  while($row = $sql_get_user_communities_result->fetch_assoc()){
    $users_to_communities_array[$counter]['community_id'] = $row['community_id'];
    $users_to_communities_array[$counter]['community_name'] = $row['community_name'];
    $users_to_communities_array[$counter]['community_description'] = $row['community_description'];
    $users_to_communities_array[$counter]['privilege'] = $row['privilege'];

    $counter = $counter + 1;
  }
  echo json_encode($users_to_communities_array); exit();

}

?>