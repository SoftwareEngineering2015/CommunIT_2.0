<?php
if( isset($_REQUEST["marker_id"]) && isset($_REQUEST["marker_name"]) ) {
	$marker_id = $_REQUEST["marker_id"];
    $marker_name = $_REQUEST["marker_name"];
} else {
	exit;
}

include("db_class.php");


$sql_community_profiles = "SELECT phone_01, phone_02, email_01, email_02, markers.pin_color FROM profiles INNER JOIN profiles_to_markers ON profiles.profile_id = profiles_to_markers.profile_id INNER JOIN markers ON profiles_to_markers.marker_id = markers.marker_id WHERE markers.marker_id = '$marker_id'";
$community_profiles_result = mysqli_query($conn,$sql_community_profiles);
			
while($row = $community_profiles_result->fetch_assoc()){
    $phone_01 =  $row['phone_01'];
    $phone_02 =  $row['phone_02'];
    $email_01 =  $row['email_01'];
    $email_02 =  $row['email_02'];
    $pin_color =  $row['pin_color'];
}

?>
<script type="text/javascript">
$("#close-slide-panel").click(function(event){
    $("#slide-panel").empty(); // Clear the div when they change their selection for which community profile they would like to edit
    var panel = $('#slide-panel');
    panel.removeClass('visible').animate({'margin-left':'-300px'}); 
  });
$("#close-icon").click(function(event){
    $("#slide-panel").empty(); // Clear the div when they change their selection for which community profile they would like to edit
    var panel = $('#slide-panel');
    panel.removeClass('visible').animate({'margin-left':'-300px'}); 
  });
</script>

<div align="right">
    <span style="font-size:1.5em; color: red; cursor: pointer;" class="glyphicon glyphicon-remove" id="close-icon"></span>
</div>
<h2 align="center" style="color: blue; font-weight: bold"> <?php echo $marker_name ?> </h2>  
<div id='street-view' style="height:100% width: 100%;">
<br /> <br /> <br /> <br /> <br /> <br /><br />
</div>
<hr style="height:1px;border:none;color:#333;background-color:#333;" />
<table>
    <tr>
        <td> Phone 1 </td>
        <td> <?php echo $phone_01; ?></td>
    </tr>
    <tr>
        <td> Phone 2 </td>
        <td> <?php echo $phone_02; ?></td>
    </tr>
    <tr>
        <td> Email 01 </td>
        <td> <?php echo $email_01; ?></td>
    </tr>
    <tr>
        <td> Email 02 </td>
        <td> <?php echo $email_02; ?></td>
    </tr>
    <tr>
        <td> Pin Color </td>
        <td> <?php echo $pin_color; ?></td>
    </tr>
</table>

<div align="center">
	<button type="button" class="btn btn-danger" id="close-slide-panel" style="width:100%">Close</button>
</div>