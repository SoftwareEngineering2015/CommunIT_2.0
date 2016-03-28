<?php
if( isset($_REQUEST["id"]) ) {
	$profile_id = $_REQUEST["id"];
} else {
	exit;
}

include("db_class.php");

$sql_community_profiles = "SELECT * FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profiles.profile_id = '$profile_id'";
$community_profiles_result = mysqli_query($conn,$sql_community_profiles);
			
while($row = $community_profiles_result->fetch_assoc()){
    $optional_email =  $row['optional_email'];
    $phone_01 =  $row['phone_01'];
    $phone_02 =  $row['phone_02'];
    $pin_color =  $row['pin_color'];
    $room_number =  $row['room_number'];
}

?>
<table class="table table-striped table-hover ">
	<tr>
    	<th> Change Community E-mail </th>
    	<td> </td>
		<td> <input type="text" class="form-control input-md" value="<?php print $optional_email ?>" placeholder="<?php print $optional_email ?>"> </td>
	</tr>
	<tr>
        <th> Change Community Phone One </th>
        <td> </td>
		<td> <input type="text" class="form-control input-md" value="<?php print $phone_01 ?>" placeholder="<?php print $phone_01 ?>"> </td>
	</tr>
	<tr>
        <th> Change Community Phone Two </th>
        <td> </td>
		<td> <input type="text" class="form-control input-md" value="<?php print $phone_02 ?>" placeholder="<?php print $phone_02 ?>"> </td>
	</tr>
	<tr>
        <th> Change Community Pin Color </th>
		<td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td> 
		<td> <input type="color" onchange="color_pin()" name="pincolor" id="pincolor" value="<?php print $pin_color ?>" placeholder="<?php print $pin_color ?>" style="width: 100%"> </td>
	</tr>
	<tr>
        <th> Change Community Room # </th>
        <td> </td>
		<td> <input type="text" class="form-control input-md" value="<?php print $room_number ?>" placeholder="<?php print $room_number ?>"> </td>
	</tr>
	<tr>
        <th> </th>
        <td> </td>
		<td> <button type="submit" class="btn btn-primary btn-md" style="width:100%">Update Community Profile</button> </td>
	</tr>
</table>