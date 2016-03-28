<?php
if( isset($_REQUEST["marker_id"]) && isset($_REQUEST["marker_name"]) ) {
	$marker_id = $_REQUEST["marker_id"];
    $marker_name = $_REQUEST["marker_name"];
} else {
	exit;
}

include("db_class.php");

$sql_get_users_id = "SELECT user_id FROM profiles INNER JOIN profiles_to_markers ON profiles.profile_id = profiles_to_markers.profile_id INNER JOIN markers ON profiles_to_markers.marker_id = markers.marker_id WHERE markers.marker_id = $marker_id";
$get_users_id_results = mysqli_query($conn,$sql_get_users_id);

$counter = 0; // Counter for the array pointer 
	
while($row = $get_users_id_results->fetch_assoc()){
    $users_id =  $row['user_id'];
    $user_information[$counter]['user_id'] = $row['user_id'];
    $sql_users = "SELECT username, phone_01, phone_02, email_01, email_02 FROM users INNER JOIN profiles ON users.user_id = profiles.user_id WHERE users.user_id = $users_id";
    $users_results = mysqli_query($conn, $sql_users);
            
    while($row = $users_results->fetch_assoc()){
        $user_information[$counter]['username'] = $row['username'];
        $user_information[$counter]['phone_01'] = $row['phone_01'];
        $user_information[$counter]['phone_02'] = $row['phone_02'];
        $user_information[$counter]['email_01'] = $row['email_01'];
        $user_information[$counter]['email_02'] = $row['email_02'];

        $counter = $counter + 1;
    }
}

?>

<script type="text/javascript">

users_username = [];
users_phone_01 = [];
users_phone_02 = [];
users_email_01 = [];
users_email_02 = [];

//populates the marker arrays with the data from the database
<?php foreach ($user_information as $value)  { ?>
  users_username.push(<?php echo '"'. $value['username'] .'"'?>);
  users_phone_01.push(<?php echo '"'. $value['phone_01'] .'"'?>);
  users_phone_02.push(<?php echo '"'. $value['phone_02'] .'"'?>);
  users_email_01.push(<?php echo '"'. $value['email_01'] .'"'?>);
  users_email_02.push(<?php echo '"'. $value['email_02'] .'"'?>);
<?php } ?>

function load_user_info(i, counter) {
    $.post( "models/jquery_floor_plan_dialog_load.php", 
        { 
            user_id: i,
        },
        function( data ) {
            $("#user_information_dialog").empty().html(data);
    });
    $("#user_information_dialog").dialog({
                modal: true,
                width: $(window).width() - 30,
                height: $(window).height() - 180,
                title: users_username[counter] + " Information",
                dialogClass: 'dialog_header', 

        });
}

$(window).resize(function() {
    $("#user_information_dialog").dialog("option", "width", $(window).width() - 30);
    $("#user_information_dialog").dialog("option", "height", $(window).height() - 180);
});

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

<style>
.dialog_header .ui-widget-header {
    background: #1995dc;
}
/* Disable jQuery UI focus glow */
*:focus {
    outline: none;
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    box-shadow: none;
}
#users_div {
    width: 100%;
    height: 25%;
    overflow-x:hidden;
}

#users_div::-webkit-scrollbar-track
{
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  border-radius: 10px;
  background-color: rgba(0,0,0,.5);
}

#users_div::-webkit-scrollbar
{
  width: 12px;
  background-color: rgba(0,0,0,.5);
}

#users_div::-webkit-scrollbar-thumb
{
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
  background-color: #1995dc;
}

</style>

<div align="right">
    <span style="font-size:1.5em; color: red; cursor: pointer;" class="glyphicon glyphicon-remove" id="close-icon"></span>
</div>

<h2 align="center" style="color: blue; font-weight: bold"> <?php echo $marker_name ?> </h2>  

<div id='street-view' style="height:100% width: 100%;">
<br /> <br /> <br /> <br /> <br /> <br /><br />
</div>

<hr style="border:none;" />

<hr style="border:none;" />

<div align="center">
    <b> Click Username To View Information </b> 
</div>

<hr style="border:none;" />

<div id="users_div" align="center">
<table class="table table-hover table-striped">

<?php
    $counter = 0;
    foreach ($user_information as $value) {
        print "<tr> <td align='center'> <a onclick='load_user_info(" . $user_information[$counter]['user_id'] . ", $counter)'> " . $value['username'] . " </a></td></tr>";
    }
?>

</table>
</div>
<br />

<div align="center">
	<button type="button" class="btn btn-danger" id="close-slide-panel" style="width:100%">Close</button>
</div>

<div id="user_information_dialog" >
</div>