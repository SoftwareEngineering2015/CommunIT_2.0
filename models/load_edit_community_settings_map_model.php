<?php

// Get the community id from the post for which community map to display
if(isset($_REQUEST["community"])) {
  $community_id = $_REQUEST["community"];
} else {
  echo "noCommunity";
  exit;
}

include("db_class.php");

$sql_does_community_exists = "SELECT * FROM communities WHERE community_id = '$community_id' LIMIT 1";
$sql_does_community_exists_result = mysqli_query($conn, $sql_does_community_exists);

if (mysqli_num_rows($sql_does_community_exists_result) == 0 ) {
  print "noCommunity"; exit();
} 

// This multidimensional array holds the information of a marker
$marker_information = array();

$sql_community_information = "SELECT * FROM markers_to_communities INNER JOIN markers ON markers_to_communities.marker_id = markers.marker_id RIGHT JOIN config ON markers_to_communities.community_id = config.community_id RIGHT JOIN communities ON communities.community_id = config.community_id WHERE communities.community_id = '$community_id'";
$community_information_result = mysqli_query($conn, $sql_community_information);

$counter = 0; // Counter for the array pointer

$community_information_result = mysqli_query($conn, $sql_community_information);
while($row = $community_information_result->fetch_assoc()){
  $marker_information[$counter]['marker_id'] = $row['marker_id'];
  $marker_information[$counter]['name'] = $row['name'];
  $marker_information[$counter]['latitude'] = $row['latitude'];
  $marker_information[$counter]['longitude'] = $row['longitude'];
  $marker_information[$counter]['location'] = $row['location'];
  $marker_information[$counter]['community_marker_color'] = $row['default_pin_color'];

  if ($row['default_pin_color_status'] == 1) {
    $marker_information[$counter]['default_pin_color_status'] = 1; 
    $marker_information[$counter]['pin_color'] = $row['default_pin_color'];
  } else {
    $marker_information[$counter]['default_pin_color_status'] = 0; 
    $marker_information[$counter]['pin_color'] = $row['pin_color'];
  }

  $marker_information[$counter]['has_floorplan'] = $row['has_floorplan'];

  $counter = $counter + 1;
}
echo json_encode($marker_information); exit();

?>