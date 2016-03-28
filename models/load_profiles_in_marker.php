<?php
if( isset($_REQUEST["marker_id"]) && isset($_REQUEST["marker_name"]) ) {
	$marker_id = $_REQUEST["marker_id"];
    $marker_name = $_REQUEST["marker_name"];
} else {
	exit;
}

include("db_class.php");

$profiles_array = array();

$sql_community_profiles = "SELECT markers.name AS marker_name, users.first_name AS first_name, users.last_name AS last_name, phone_01, phone_02, email_01, email_02, markers.pin_color FROM profiles INNER JOIN profiles_to_markers ON profiles.profile_id = profiles_to_markers.profile_id INNER JOIN users ON profiles.user_id = users.user_id INNER JOIN markers ON profiles_to_markers.marker_id = markers.marker_id WHERE markers.marker_id = '$marker_id' AND has_edited != 0";
$community_profiles_result = mysqli_query($conn,$sql_community_profiles);

if (mysqli_num_rows($community_profiles_result) > 0 ) {	
    $counter = 0;
    while($row = $community_profiles_result->fetch_assoc()){
        $profiles_array[$counter]['marker_name'] =  $row['marker_name'];
        $profiles_array[$counter]['residents_name'] =  $row['first_name'] . " " . $row['last_name'];
        $profiles_array[$counter]['phone_01'] =  $row['phone_01'];
        $profiles_array[$counter]['phone_02'] =  $row['phone_02'];
        $profiles_array[$counter]['email_01'] =  $row['email_01'];
        $profiles_array[$counter]['email_02'] =  $row['email_02'];

        $counter++;
    }
    echo json_encode($profiles_array); exit();
} else {
    $profiles_array['no_profiles'] = "There are no profiles for " . $marker_name . ".";
    echo json_encode($profiles_array); exit();
}

?>