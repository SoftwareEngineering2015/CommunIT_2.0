<?php
if( isset($_REQUEST["marker_id"]) && isset($_REQUEST["marker_name"]) ) {
	$marker_id = $_REQUEST["marker_id"];
    $marker_name = $_REQUEST["marker_name"];
} else {
	exit;
}

include("db_class.php");

$sql_floorplans_in_marker = "SELECT * FROM floorplans_to_markers INNER JOIN floor_plans ON floorplans_to_markers.floorplan_id = floor_plans.floorplan_id WHERE floorplans_to_markers.marker_id = '$marker_id' ORDER BY floor";
$sql_floorplans_in_marker_result = mysqli_query($conn, $sql_floorplans_in_marker);

if (mysqli_num_rows($sql_floorplans_in_marker_result) > 0 ) {  
    $counter = 0;
    while($row = $sql_floorplans_in_marker_result->fetch_assoc()){
        $floorplan_information[$counter]['floorplan_id'] = $row['floorplan_id'];
        $floorplan_information[$counter]['floor'] = $row['floor'];
        $counter++;
    } 
    echo json_encode($floorplan_information); exit();
}else {
    $floorplan_information['no_profiles'] = "There are no floorplans in " . $marker_name . ".";
    echo json_encode($floorplan_information); exit();
}

?>