<?php
//Bring the db_class into this file.
require_once 'db_class.php';

//Here we are grabbing json encoded object form profileController.js.
//Note: We are getting the user_id here and nothing else.
$postdata = file_get_contents("php://input");
   //Once we get the data we have to decode it.
   $request = json_decode($postdata);
   @$user = $request->user;

$checkQuery = "SELECT * FROM profiles WHERE user_id = '$user'";
$resultCheck = $conn->query($checkQuery) or exit("Error code ({$conn->errno}): {$conn->error}");


if (mysqli_fetch_row($resultCheck)) {

        //Here we build the query. We use $user from above to grab all profile infomation for each marker they have.
        $query = "SELECT markers.marker_id, profiles.profile_id, `has_edited`, `markers`.`name` as 'marker_name', phone_01, phone_02, email_01, email_02, pin_color, username, name, location, community_name, users.password, users.email
              FROM profiles, markers, profiles_to_markers, users, config, communities, markers_to_communities
              WHERE profiles.user_id = '$user'
              AND profiles.user_id = users.user_id
			        AND markers.marker_id = profiles_to_markers.marker_id
              AND profiles.profile_id = profiles_to_markers.profile_id
              AND config.community_id = communities.community_id
              AND markers_to_communities.marker_id = markers.marker_id
              AND communities.community_id = markers_to_communities.community_id
              GROUP BY markers.marker_id
              ORDER BY profiles_to_markers.has_edited ASC";

        //Do the query.
        $results = mysqli_query($conn, $query);

    if (mysqli_fetch_row($results)) {

        //Do the query.
        $results = mysqli_query($conn, $query);

        //Now we use mysqli_fetch_assoc to cleanly lay-out the result so we can send it over
        //to the controller.
        while($row = mysqli_fetch_assoc($results)) {
                $json_profile_array[] =  array(
                               "marker_id" => $row['marker_id'],
                               "marker_name" => $row['marker_name'],
                               "profile_id" => $row['profile_id'],
                               "phone_01" => $row['phone_01'],
                               "phone_02" => $row['phone_02'],
                               "email_01" => $row['email_01'],
                               "email_02" => $row['email_02'],
                               "pin_color" => $row['pin_color'],
                               "username" => $row['username'],
                               "name" => $row['name'],
                               "location" => $row['location'],
                               "community_name" => $row['community_name'],
                               "password" => $row['password'],
                               "email" => $row['email'],
                               "has_edited" => $row['has_edited']);
         }

         //Here we encode our "json object" and send itto profileController.js.
  }else{
    $json_profile_array = array (
        "error" => "No profiles for this account"
    );
  }
} else {
    $json_profile_array = array (
        "error" => "No profiles for this account"
    );
}

 header('content-type: application/json');
 echo json_encode($json_profile_array, JSON_PRETTY_PRINT);

?>
