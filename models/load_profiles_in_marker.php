<?php
if( isset($_REQUEST["marker_id"]) && isset($_REQUEST["marker_name"]) ) {
	$marker_id = $_REQUEST["marker_id"];
    $marker_name = $_REQUEST["marker_name"];
} else {
	exit;
}

include("db_class.php");

$profiles_array = array();

$sql_community_profiles = "SELECT profiles.profile_id AS profile_id, markers.name AS marker_name, markers.miscinfo AS marker_miscinfo, users.first_name AS first_name, users.last_name AS last_name, profiles.miscinfo AS miscinfo, profiles.phone_01 AS phone_01, profiles.phone_02 AS phone_02, profiles.email_01 AS email_01, profiles.email_02 AS email_02, markers.name as marker_name, markers.location as marker_location  FROM profiles INNER JOIN profiles_to_markers ON profiles.profile_id = profiles_to_markers.profile_id INNER JOIN users ON profiles.user_id = users.user_id INNER JOIN markers ON profiles_to_markers.marker_id = markers.marker_id WHERE markers.marker_id = '$marker_id' AND profiles.has_edited != 0 ORDER BY users.user_id";
$community_profiles_result = mysqli_query($conn,$sql_community_profiles);

if (mysqli_num_rows($community_profiles_result) > 0 ) {	
    $counter = 0;
    while($row = $community_profiles_result->fetch_assoc()){
        $profiles_array[$counter]['marker_name'] =  $row['marker_name'];
        $profiles_array[$counter]['marker_miscinfo'] =  $row['marker_miscinfo'];
        $profiles_array[$counter]['marker_location'] =  $row['marker_location'];
        $profiles_array[$counter]['marker_name'] =  $row['marker_name'];
        $profiles_array[$counter]['residents_name'] =  $row['first_name'] . " " . $row['last_name'];
        $profiles_array[$counter]['phone_01'] =  $row['phone_01'];
        $profiles_array[$counter]['phone_02'] =  $row['phone_02'];
        $profiles_array[$counter]['email_01'] =  $row['email_01'];
        $profiles_array[$counter]['email_02'] =  $row['email_02'];
        $profiles_array[$counter]['miscinfo'] =  $row['miscinfo'];

        $sql_sub_resident_profiles = "SELECT * FROM residents WHERE profile_id = '" .$row['profile_id'] ."'";
        $sql_sub_resident_profiles_result = mysqli_query($conn,$sql_sub_resident_profiles);
        if (mysqli_num_rows($sql_sub_resident_profiles_result) > 0 ) {
            while($row2 = $sql_sub_resident_profiles_result->fetch_assoc()){
                $counter++;
                $profiles_array[$counter]['residents_name'] =  $row2['firstname'] . " " . $row2['lastname'];
                $profiles_array[$counter]['phone_01'] =  $row2['phone_01'];
                $profiles_array[$counter]['phone_02'] =  $row2['phone_02'];
                $profiles_array[$counter]['email_01'] =  $row2['email_01'];
                $profiles_array[$counter]['email_02'] =  $row2['email_02'];
            }
        }

        $counter++;
    }
    echo json_encode($profiles_array); exit();
} else {
    $sql_get_marker_info = "SELECT * FROM markers WHERE marker_id='$marker_id' LIMIT 1";
    $sql_get_marker_info_result = mysqli_query($conn,$sql_get_marker_info);
    
    while ($row = $sql_get_marker_info_result -> fetch_assoc()) {
        $profiles_array['marker_name'] =  $row['name'];
        $profiles_array['marker_miscinfo'] =  $row['miscinfo'];
        $profiles_array['marker_location'] =  $row['location'];
    }
    $profiles_array['no_profiles'] = "There are no residents for " . $marker_name . ".";
    echo json_encode($profiles_array); exit();
}

?>