<?php
   if (!isset($_REQUEST['user']) && !isset($_REQUEST['get'])) {
   	exit;
   }

   include('db_class.php');

   $user_id = $_REQUEST['user'];
   $user_id = stripslashes($user_id);
   $user_id = mysql_real_escape_string($user_id);

   $get = $_REQUEST['get'];
   $get = stripslashes($get);
   $get = mysql_real_escape_string($get);

   if($get == "residents"){
      $profile_id = $_REQUEST['profile'];
      $profile_id = stripslashes($profile_id);
      $profile_id = mysql_real_escape_string($profile_id);
   }

   if($get == "update"){
      $profile_id = $_REQUEST['profile'];
      $profile_id = stripslashes($profile_id);
      $profile_id = mysql_real_escape_string($profile_id);

      $firstName = $_REQUEST['firstName'];
      $firstName = stripslashes($firstName);
      $firstName = mysql_real_escape_string($firstName);

      $lastName = $_REQUEST['lastName'];
      $lastName = stripslashes($lastName);
      $lastName = mysql_real_escape_string($lastName);

      $phone_01 = $_REQUEST['phone_01'];
      $phone_01 = stripslashes($phone_01);
      $phone_01 = mysql_real_escape_string($phone_01);

      $phone_02 = $_REQUEST['profile'];
      $phone_02 = stripslashes($phone_02);
      $phone_02 = mysql_real_escape_string($phone_02);

      $email_01 = $_REQUEST['profile'];
      $email_01 = stripslashes($email_01);
      $email_01 = mysql_real_escape_string($email_01);

      $email_02 = $_REQUEST['profile'];
      $email_02 = stripslashes($email_02);
      $email_02 = mysql_real_escape_string($email_02);

   }


$checkQuery = "SELECT * FROM profiles WHERE user_id = '$user_id'";
$resultCheck = $conn->query($checkQuery) or exit("Error code ({$conn->errno}): {$conn->error}");

//LASH NUMBER 6303709313
//ADMIN ID 4BFCDE5BABC5

if (mysqli_fetch_row($resultCheck)) {

    if($get == "profile"){

        $query = "SELECT `communities`.`community_id`, `markers`.`marker_id`, `profiles`.`profile_id`, `markers`.`name` as 'marker_name', location, community_name
        FROM profiles, markers, profiles_to_markers, users, config, communities, markers_to_communities
        WHERE profiles.user_id = '$user_id'
        AND profiles.user_id = users.user_id
        AND markers.marker_id = profiles_to_markers.marker_id
        AND profiles.profile_id = profiles_to_markers.profile_id
        AND config.community_id = communities.community_id
        AND markers_to_communities.marker_id = markers.marker_id
        AND communities.community_id = markers_to_communities.community_id";

        $results = mysqli_query($conn, $query);

    if (mysqli_fetch_row($results)) {

        $results = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($results)) {
                $json_array[] =  array(
                               "community_id" => $row['community_id'],
                               "marker_id" => $row['marker_id'],
                               "profile_id" => $row['profile_id'],
                               "marker_name" => $row['marker_name'],
                               "location" => $row['location'],
                               "community_name" => $row['community_name']);
         }

  }else{
    $json_array = array (
        "error" => "No profiles for this account"
    );
  }
}else if($get == "residents"){
  $query = "SELECT resident_id, `profiles`.`profile_id`, `residents`.`firstname`, `residents`.`lastname`, `residents`.`phone_01`, `residents`.`phone_02`, `residents`.`email_01`, `residents`.`email_02`
  FROM profiles, residents
  WHERE profiles.profile_id = '$profile_id'
  AND residents.profile_id = profiles.profile_id";

  $results = mysqli_query($conn, $query);

  if (mysqli_fetch_row($results)) {

    $results = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($results)) {
            $json_array[$row['resident_id']] =  array(
                                   "profile_id" => $row['profile_id'],
                                   "resident_id" => $row['resident_id'],
                                   "firstname" => $row['firstname'],
                                   "lastname" => $row['lastname'],
                                   "phone_01" => $row['phone_01'],
                                   "phone_02" => $row['phone_02'],
                                   "email_01" => $row['email_01'],
                                   "email_02" => $row['email_02']);
     }

  }else{
    $json_array = array (
      "error" => "Residents For this account"
    );
  }
}else if($get == "update"){
  if (mysqli_fetch_row($results)) {
  }else {
      $json_array = array (
          "error" => "Error updating resident, please try again."
      );
  }
}else if($get == "insert"){
  if (mysqli_fetch_row($results)) {
  }else {
      $json_array = array (
          "error" => "Error adding resident, please try again."
      );
  }
}else if($get == "delete"){
  if (mysqli_fetch_row($results)) {
  }else {
      $json_array = array (
          "error" => "Error deleting resident, please try again."
      );
  }
}else{
    $json_array = array (
        "error" => "Error, please try again."
    );
}
}else{
    $json_array = array (
        "error" => "Error, please try again."
    );
}

 header('content-type: application/json');
 echo json_encode($json_array, JSON_PRETTY_PRINT);

?>
