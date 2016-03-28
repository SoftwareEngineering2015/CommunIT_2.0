<?php

// Get the community id from the post for which community map to display
if (isset($_REQUEST["community"])) {
    $community_id = $_REQUEST["community"];
} else {
    echo "noCommunity";
    exit;
}

include("db_class.php");

// This multidimensional array holds the information of a marker
$json_array            = array();
$community_information = array();
$marker_information    = array();

$sql_does_community_exists        = "SELECT * FROM communities INNER JOIN config ON communities.community_id = config.community_id WHERE communities.community_id = '$community_id' LIMIT 1";
$sql_does_community_exists_result = mysqli_query($conn, $sql_does_community_exists);

if (mysqli_num_rows($sql_does_community_exists_result) == 0) {
    print "noCommunity";
    exit();
} else {
    $sql_does_community_exists_result = mysqli_query($conn, $sql_does_community_exists);
    while ($row = $sql_does_community_exists_result->fetch_assoc()) {
        $community_information['community_name'] = $row['community_name'];
    }
    $sql_community_information    = "SELECT * FROM markers_to_communities INNER JOIN markers ON markers_to_communities.marker_id = markers.marker_id WHERE markers_to_communities.community_id = '$community_id'";
    $community_information_result = mysqli_query($conn, $sql_community_information);
    
    $counter = 0; // Counter for the array pointer
    
    if (mysqli_num_rows($community_information_result) == 0) {
        $json_array['community_information'] = $community_information;
        $json_array['no_markers']    = "";
        echo json_encode($json_array);
        exit();
    } else {
        $community_information_result = mysqli_query($conn, $sql_community_information);
        while ($row = $community_information_result->fetch_assoc()) {
            $marker_information[$counter]['marker_id']      = $row['marker_id'];
            $marker_information[$counter]['name']           = $row['name'];
            $marker_information[$counter]['latitude']       = $row['latitude'];
            $marker_information[$counter]['longitude']      = $row['longitude'];
            $marker_information[$counter]['location']       = $row['location'];
            $marker_information[$counter]['pin_color']      = $row['pin_color'];
            $marker_information[$counter]['has_floorplan']  = $row['has_floorplan'];
            
            $counter = $counter + 1;
        }
        $json_array['community_information'] = $community_information;
        $json_array['marker_information']    = $marker_information;
        echo json_encode($json_array);
        exit();
    }
}

?>