<?php
if( isset($_REQUEST["user_id"])) {
    $user_id = $_REQUEST["user_id"];
} else {
  exit;
}

include("db_class.php");

$sql_profile_information = "SELECT phone_01, phone_02, email_01, email_02 FROM profiles WHERE user_id = $user_id";
$profile_information_result = mysqli_query($conn,$sql_profile_information);

?>

<table class="table table-hover table-striped">

<?php

while($row = $profile_information_result->fetch_assoc()){
    print "<tr> <th> Phone 01 </th> <td> " . $row['phone_01'] . "</td>";
    print "<tr> <th> Phone 02 </th> <td> " . $row['phone_02'] . "</td>";
    print "<tr> <th> E-mail 01 </th> <td> " . $row['email_01'] . "</td>";
    print "<tr> <th> E-mail 02 </th> <td> " . $row['email_02'] . "</td></tr>";
}

?>

</table>