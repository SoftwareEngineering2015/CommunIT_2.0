<?php

// Get the community id from the post for which community map to display
if (isset($_REQUEST["floorplan"])) {
    $floorplan_id = $_REQUEST["floorplan"];
    if (isset($_REQUEST["default_pin_color"])) {
        $default_pin_color = $_REQUEST["default_pin_color"];
    } else {
        $default_pin_color = "";
    }
} else {
    echo "noFloorplan";
    exit;
}

include("db_class.php");

// This multidimensional array holds the information of a marker
$json_array            = array();
$floorplan_information = array();
$marker_information    = array();

$sql_get_floorplan_information        = "SELECT * FROM floor_plans WHERE floorplan_id = '$floorplan_id' LIMIT 1";
$sql_get_floorplan_information_result = mysqli_query($conn, $sql_get_floorplan_information);

while ($row = $sql_get_floorplan_information_result->fetch_assoc()) {
    $floorplan_information['floor']          = $row['floor'];
    $floorplan_information['image_location'] = $row['image_location'];
}
$sql_marker_information        = "SELECT * FROM markers_to_floorplans INNER JOIN markers ON markers_to_floorplans.marker_id = markers.marker_id WHERE markers_to_floorplans.floorplan_id = '$floorplan_id'";
$sql_marker_information_result = mysqli_query($conn, $sql_marker_information);

$counter = 0; // Counter for the array pointer

if (mysqli_num_rows($sql_marker_information_result) == 0) {
    $json_array['floorplan_information'] = $floorplan_information;
    $json_array['no_markers']            = "";
    echo json_encode($json_array);
    exit();
} else {
    $sql_marker_information_result = mysqli_query($conn, $sql_marker_information);
    while ($row = $sql_marker_information_result->fetch_assoc()) {
        $marker_information[$counter]['marker_id'] = $row['marker_id'];
        $marker_information[$counter]['name']      = $row['name'];
        $marker_information[$counter]['latitude']  = $row['latitude'];
        $marker_information[$counter]['longitude'] = $row['longitude'];
        $marker_information[$counter]['location']  = $row['location'];
        if ($default_pin_color != "") {
            $marker_information[$counter]['pin_color'] = $default_pin_color;
        } else {
            $marker_information[$counter]['pin_color'] = $row['pin_color'];
        }
        
        $counter = $counter + 1;
    }
    $json_array['floorplan_information'] = $floorplan_information;
    $json_array['marker_information']    = $marker_information;
    echo json_encode($json_array);
    exit();
}

?>