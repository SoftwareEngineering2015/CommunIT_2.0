<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];

$sql_get_markers_in_community = "SELECT * FROM markers_to_communities WHERE community_id = '$community_id'";
$sql_get_markers_in_community_result = mysqli_query($conn, $sql_get_markers_in_community);

while ($row = $sql_get_markers_in_community_result->fetch_assoc()) {
	$sql_delete_markers_in_community = "DELETE FROM markers WHERE marker_id = '" . $row['marker_id']. "'";

	if(mysqli_query($conn, $sql_delete_markers_in_community)) {
		continue;
	} else {
		echo "failed"; exit();
	}
}


$sql_delete_community = "DELETE FROM communities WHERE community_id = '$community_id'";

if(mysqli_query($conn, $sql_delete_community)) {
	echo "success"; exit();
} else {
	echo "failed"; exit();
}
?>