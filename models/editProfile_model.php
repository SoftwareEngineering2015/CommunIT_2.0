<?php

//Bring the db_class into this file.
require_once 'db_class.php';

//Here we are grabbing json encoded object form profileController.js.
//Note: We are getting the user_id here and nothing else.
$postdata = file_get_contents("php://input");
   //Once we get the data we have to decode it.
   $request = json_decode($postdata);
   @$marker_id = $request->marker_id;
   @$profile_id = $request->profile_id;
   @$phone_01 = $request->phone_01;
   @$phone_02 = $request->phone_02;
   @$email_01 = $request->email_01;
   @$email_02 = $request->email_02;
   @$pin_color = $request->pin_color;
   @$password = $request->password;
   @$email = $request->email;
   @$user_id = $request->user_id;

 //For testing purposes....
 /*echo ($marker_id . " ");
 echo ($profile_id . " ");
 echo ($phone_01 . " ");
 echo ($phone_02 . " ");
 echo ($email_01 . " ");
 echo ($email_02 . " ");
 echo ($pin_color . " ");
 echo ($password . " ");*/

//Sanitization
 $marker_id = stripslashes($marker_id);
 $profile_id = stripslashes($profile_id);
 $phone_01 = stripslashes($phone_01);
 $phone_02 = stripslashes($phone_02);
 $email_01 = stripslashes($email_01);
 $email_02 = stripslashes($email_02);
 $pin_color = stripslashes($pin_color);
 $password = stripslashes($password);
 $email = stripslashes($email);
 $user_id = stripslashes($user_id);

 $marker_id = mysql_real_escape_string($marker_id);
 $profile_id = mysql_real_escape_string($profile_id);
 $phone_01 = mysql_real_escape_string($phone_01);
 $phone_02 = mysql_real_escape_string($phone_02);
 $email_01 = mysql_real_escape_string($email_01);
 $email_02 = mysql_real_escape_string($email_02);
 $pin_color = mysql_real_escape_string($pin_color);
 $password = mysql_real_escape_string($password);
 $email = mysql_real_escape_string($email);
 $user_id = mysql_real_escape_string($user_id);

 //these do the update queries only when the select field is not empty.

 $query = "UPDATE `profiles` SET `phone_01`= '$phone_01',`phone_02`= '$phone_02',`email_01`= '$email_01',`email_02`= '$email_02' WHERE profile_id = '$profile_id'";
 $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");

 hasEdited($conn, $profile_id);
/*
 if ($phone_01 != null) {
     //echo ("Made it here: phone_01");
     $query = "UPDATE profiles SET phone_01 = '$phone_01' WHERE profile_id = '$profile_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
     hasEdited($conn, $profile_id);
 }

 if ($phone_02 != null) {
     //echo ("Made it here: phone_02");
     $query = "UPDATE profiles SET phone_02 = '$phone_02' WHERE profile_id = '$profile_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }

 if ($email_01 != null) {
     //echo ("Made it here: email_01");
     $query = "UPDATE `profiles` SET `email_01` = '$email_01' WHERE profile_id = '$profile_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
     hasEdited($conn, $profile_id);
 }

 if ($email_02 != null) {
     //echo ("Made it here: email_02");
     $query = "UPDATE profiles SET email_02 = '$email_02' WHERE profile_id = '$profile_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }
*/
 if ($pin_color != null) {
     //echo ("Made it here: pin_color");
     $query = "UPDATE markers SET pin_color = '$pin_color' WHERE marker_id = '$marker_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }

 if ($password != null) {
     //echo ("Made it here: password");
     $query = "UPDATE users SET password = '$password' WHERE user_id = '$user_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }

 if ($email != null) {
     //echo ("Made it here: email");
     $query = "UPDATE users SET email = '$email' WHERE user_id = '$user_id'";
     $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }


 function hasEdited($conn, $profile_id){
   $query = "UPDATE profiles_to_markers SET has_edited = 1 WHERE profile_id = '$profile_id'";
   $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
 }

?>
