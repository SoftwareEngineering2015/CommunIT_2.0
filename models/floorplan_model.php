<?php

//Use if you're checking for input to be used in the SQL Query
if (!isset($_REQUEST["input_marker"])) {
    exit;
} else {
    $input_marker = $_REQUEST["input_marker"];
    if (isset($_REQUEST['floorplan'])) {
        $floorplan = $_REQUEST['floorplan'];
    } else {
        $floorplan = 0;
    }
}

//Has the database configuration
include('db_class.php');

//The marker that was clicked on within the community.
//$input_marker = '111111111111';

//Selects all floorplans that are tied to that building


$sql_get_floorplans    = "SELECT * FROM `floorplans_to_markers` WHERE `marker_id` = '$input_marker' ";
$result_get_floorplans = mysqli_query($conn, $sql_get_floorplans);

//Checking to see if any floor plans exist for that marker.
if (mysqli_num_rows($result_get_floorplans) < 1) {
    $floorcheck = false;
    echo "Error: No floor plans exist for this building.";
} else {
    //Adds each record to an associative Array
    while ($row = mysqli_fetch_assoc($result_get_floorplans)) {
        $jsonArr_floorplans[] = array(
            "floorplan_id" => $row['floorplan_id']
        );
    }
    //print_r($jsonArr_floorplans);
    foreach ($jsonArr_floorplans as $key => $floor) {
        $floorvar = $floor['floorplan_id'];
        
        $sql_get_floors    = "SELECT * FROM `floor_plans` WHERE `floorplan_id` = '$floorvar' ";
        $result_get_floors = mysqli_query($conn, $sql_get_floors);
        
        if (mysqli_num_rows($result_get_floors) < 1) {
            $floorcheck = false;
            echo "Error: No floors?";
        } else {
            //Adds each record to an associative Array
            while ($row = mysqli_fetch_assoc($result_get_floors)) {
                $jsonArr_floors[] = array(
                    "floorplan_id" => $row['floorplan_id'],
                    "floor" => $row['floor'],
                    "image_location" => $row['image_location']
                );
            }
        }
        
        if ($floorplan == $key) {
            $sql_get_floorplan_markers    = "SELECT * FROM `markers_to_floorplans`,`markers` WHERE `floorplan_id` = '$floorvar' AND markers_to_floorplans.marker_id = markers.marker_id GROUP BY markers.marker_id";
            $result_get_floorplan_markers = mysqli_query($conn, $sql_get_floorplan_markers);
            
            while ($row = mysqli_fetch_assoc($result_get_floorplan_markers)) {
                
                $jsonArr_floorplan_markers[] = array(
                    "marker_id" => $row['marker_id'],
                    "name" => $row['name'],
                    "latitude" => $row['latitude'],
                    "longitude" => $row['longitude'],
                    "location" => $row['location'],
                    "pin_color" => $row['pin_color'],
                    "has_floorplan" => $row['has_floorplan']
                );
            }
        }
    }
    
    //$floorplans_encode = json_encode($jsonArr_floors,JSON_PRETTY_PRINT);
    //$markers_encode = json_encode($jsonArr_floorplan_markers,JSON_PRETTY_PRINT);
    
    $json_data['floor_data']  = $jsonArr_floors;
    $json_data['marker_data'] = $jsonArr_floorplan_markers;
    
    
    
    //Displays format in JSON
    header('content-type: application/json');
    //Converts the Associative Array into JSON
    //echo json_encode($jsonArr_floorplans, JSON_PRETTY_PRINT);
    echo json_encode($json_data, JSON_PRETTY_PRINT);
    //echo json_encode($jsonArr_floors);
    
    
    
    
    
}



?>