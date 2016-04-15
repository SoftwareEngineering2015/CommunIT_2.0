<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['floorplan'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$floorplan_id = $_REQUEST['floorplan'];

$sql_get_markers_in_floorplan = "SELECT * FROM markers_to_floorplans WHERE floorplan_id = '$floorplan_id'";
$sql_get_markers_in_floorplan_result = mysqli_query($conn, $sql_get_markers_in_floorplan);

while ($row = $sql_get_markers_in_floorplan_result->fetch_assoc()) {
	$sql_delete_markers_in_floorplan = "DELETE FROM markers WHERE marker_id = '" . $row['marker_id']. "'";

	if(mysqli_query($conn, $sql_delete_markers_in_floorplan)) {
		continue;
	} else {
		echo "failed"; exit();
	}
}

$sql_get_image = "SELECT image_location FROM floor_plans WHERE floorplan_id = '$floorplan_id'";
$sql_get_image_result = mysqli_query($conn, $sql_get_image);

while ($row = $sql_get_image_result->fetch_assoc()) {
  	$image = "../". $row['image_location'];
}

$sql_delete_floorplan = "DELETE FROM floor_plans WHERE floorplan_id = '$floorplan_id'";

if(mysqli_query($conn, $sql_delete_floorplan)) {
	if (unlink($image)) {
		echo "success"; exit();
	} else {
		echo "failed"; exit();
	}
} else {
    echo "failed"; exit();
}
?>