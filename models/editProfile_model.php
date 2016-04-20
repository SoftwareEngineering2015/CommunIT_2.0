<?php

//Bring the db_class into this file.
require_once 'db_class.php';

$postdata = file_get_contents("php://input");
   //Once we get the data we have to decode it.
   $request = json_decode($postdata);
   @$marker_id = $request->marker_id;
   @$profile_id = $request->profile_id;
   @$phone_01 = $request->phone_01;
   @$phone_02 = $request->phone_02;
   @$email_01 = $request->email_01;
   @$email_02 = $request->email_02;
   @$miscinfo = $request->miscinfo;
   @$pin_color = $request->pin_color;
   @$user_id = $request->user_id;

 $marker_id = stripslashes($marker_id);
 $profile_id = stripslashes($profile_id);
 $phone_01 = stripslashes($phone_01);
 $phone_02 = stripslashes($phone_02);
 $email_01 = stripslashes($email_01);
 $email_02 = stripslashes($email_02);
 $miscinfo = stripslashes($miscinfo);
 $pin_color = stripslashes($pin_color);
 $user_id = stripslashes($user_id);

 $marker_id = mysql_real_escape_string($marker_id);
 $profile_id = mysql_real_escape_string($profile_id);
 $phone_01 = mysql_real_escape_string($phone_01);
 $phone_02 = mysql_real_escape_string($phone_02);
 $email_01 = mysql_real_escape_string($email_01);
 $email_02 = mysql_real_escape_string($email_02);
 $miscinfo = mysql_real_escape_string($miscinfo);
 $pin_color = mysql_real_escape_string($pin_color);
 $user_id = mysql_real_escape_string($user_id);


 if($phone_02 == ''){
   $phone_02 == NULL;
 }
 if($email_02 == ''){
   $email_02 == NULL;
}
 if($pin_color == ''){
   $pin_color == NULL;
 }

 $query = "UPDATE `profiles` SET `phone_01`= '$phone_01',`phone_02`= '$phone_02',`email_01`= '$email_01',`email_02`= '$email_02', `miscinfo`= '$miscinfo' WHERE profile_id = '$profile_id' AND `user_id` = '$user_id'";
 $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");

 hasEdited($conn, $profile_id);

 if ($pin_color != null) {
     //echo ("Made it here: pin_color");
     $query = "UPDATE markers SET pin_color = '$pin_color' WHERE marker_id = '$marker_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }

 function hasEdited($conn, $profile_id){
   $query = "UPDATE profiles SET has_edited = 1 WHERE profile_id = '$profile_id'";
   $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }

?>
