<?php

   include('db_class.php');

   //Get JSON data from controller
   $postdata = file_get_contents("php://input");
   //Decode JSON Data
   $request = json_decode($postdata);

   //Sanitizing inputs
   @$user_id = $request->user;
   $user_id = stripslashes($user_id);
   $user_id = mysql_real_escape_string($user_id);

   @$command = $request->command;
   $command = stripslashes($command);
   $command = mysql_real_escape_string($command);

   if($command == "residents" || $command == "delete"){
      @$profile_id = $request->profile;
      $profile_id = stripslashes($profile_id);
      $profile_id = mysql_real_escape_string($profile_id);
   }

   if($command == "update" || $command == "delete"){

     @$resident_id = $request->resident;
     $resident_id = stripslashes($resident_id);
     $resident_id = mysql_real_escape_string($resident_id);
   }

   if($command == "update" || $command == "insert"){
      @$profile_id = $request->profile;
      $profile_id = stripslashes($profile_id);
      $profile_id = mysql_real_escape_string($profile_id);

      @$firstname = $request->firstname;
      $firstname = stripslashes($firstname);
      $firstname = mysql_real_escape_string($firstname);

      @$lastname = $request->lastname;
      $lastname = stripslashes($lastname);
      $lastname = mysql_real_escape_string($lastname);

      @$phone_01 = $request->phone_01;
      $phone_01 = stripslashes($phone_01);
      $phone_01 = mysql_real_escape_string($phone_01);
      if($phone_01 == ''){
        $phone_01 == NULL;
      }

      @$phone_02 = $request->phone_02;
      $phone_02 = stripslashes($phone_02);
      $phone_02 = mysql_real_escape_string($phone_02);
      if($phone_02 == ''){
        $phone_02 == NULL;
      }

      @$email_01 = $request->email_01;
      $email_01 = stripslashes($email_01);
      $email_01 = mysql_real_escape_string($email_01);
      if($email_01 == ''){
        $email_01 == NULL;
      }

      @$email_02 = $request->email_02;
      $email_02 = stripslashes($email_02);
      $email_02 = mysql_real_escape_string($email_02);
      if($email_02 == ''){
        $email_02 == NULL;
      }

   }

//Making sure the user_id exists




$checkQuery = "SELECT `profile_id` FROM profiles WHERE user_id = '$user_id'";
$resultCheck = $conn->query($checkQuery) or exit("Error code ({$conn->errno}): {$conn->error}");

if (mysqli_fetch_row($resultCheck)) {

  if($command == "checkHasEdited"){
    $checkQuery = "SELECT `profile_id`, `has_edited` FROM profiles WHERE user_id = '$user_id'";
    $resultCheck = $conn->query($checkQuery) or exit("Error code ({$conn->errno}): {$conn->error}");

    if (mysqli_fetch_row($resultCheck)) {
      $resultCheck = $conn->query($checkQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
      while($row = mysqli_fetch_assoc($resultCheck)) {
        $json_array[] =  array(
        "has_edited" => $row['has_edited']
      );
    }
    }else{
      $json_array = array(
        "error" => "No profiles for this account."
      );
    }
  }else if($command == "profiles"){

        $query = "SELECT markers.marker_id, profiles.profile_id, `communities`.`community_id`, `profiles`.`has_edited`, `markers`.`name` as 'marker_name', location, community_name, `users`.`first_name`, `users`.`last_name`, `profiles`.`phone_01`, `profiles`.`email_01`, `profiles`.`phone_02`, `profiles`.`email_02`
              FROM profiles
              INNER JOIN users ON profiles.user_id = users.user_id
              INNER JOIN communities ON profiles.community_id = communities.community_id
              INNER JOIN config ON communities.community_id = config.community_id
              LEFT JOIN profiles_to_markers ON profiles.profile_id = profiles_to_markers.profile_id
              LEFT JOIN markers ON profiles_to_markers.marker_id = markers.marker_id
              WHERE profiles.user_id = '$user_id'
              GROUP BY profiles.profile_id
              ORDER BY community_name ASC";

        $results = mysqli_query($conn, $query);

    if (mysqli_fetch_row($results)) {

        $results = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($results)) {
                $json_array[] =  array(
                               "community_id" => $row['community_id'],
                               "marker_id" => $row['marker_id'],
                               "profile_id" => $row['profile_id'],
                               "first_name" => $row['first_name'],
                               "last_name" => $row['last_name'],
                               "phone_01" => $row['phone_01'],
                               "email_01" => $row['email_01'],
                               "phone_02" => $row['phone_02'],
                               "email_02" => $row['email_02'],
                               "marker_name" => $row['marker_name'],
                               "location" => $row['location'],
                               "community_name" => $row['community_name']);
         }

  }else{
    $json_array = array (
        "error" => "No profiles for this account"
    );
  }
}else if($command == "residents"){
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
      "error" => "No residents for this account"
    );
  }

}else if($command == "update"){
  $query = "UPDATE `residents`
  SET `firstname` = '$firstname' ,`lastname` = '$lastname',`phone_01` = '$phone_01',
  `phone_02` = '$phone_02',`email_01` = '$email_01',`email_02` = '$email_02'
  WHERE `resident_id` = '$resident_id' AND `profile_id` = '$profile_id'";

  $results = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");

  if ($results) {

    $json_array = array (
        "success" => "Resident, updated successfully."
    );

  }else {
      $json_array = array (
          "error" => "Error updating resident, please try again."
      );
  }
}else if($command == "insert"){
  $residentOverload = true;
  $query = "SELECT count(`profile_id`) as 'resident_count' FROM `residents` WHERE `profile_id` = '$profile_id'";

  $results = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
  while($row = mysqli_fetch_assoc($results)) {
    if ($row['resident_count'] < 25){
      $residentOverload = false;
    }
  }
  if ($residentOverload == false) {
    $query = "INSERT INTO `residents`
    (`profile_id`, `firstname`, `lastname`, `phone_01`, `phone_02`, `email_01`, `email_02`)
    VALUES ('$profile_id','$firstname','$lastname','$phone_01','$phone_02','$email_01','$email_02')";

    $results = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");

    if (mysqli_affected_rows($conn)) {

      $json_array = array (
          "success" => "Resident, added successfully."
      );
    }else {
        $json_array = array (
            "error" => "Error adding resident, please try again."
        );
    }
  }else {
      $json_array = array (
          "error" => "Error: To many residents (MAX: 25). Try removing some first."
      );
  }
}else if($command == "delete"){
  $query = "DELETE FROM `residents` WHERE resident_id = '$resident_id' AND `profile_id` = '$profile_id'";
  $results = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");

  if (mysqli_affected_rows($conn)) {
    $json_array = array (
        "success" => "Resident, removed successfully."
    );
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
