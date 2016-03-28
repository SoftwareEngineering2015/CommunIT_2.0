<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['community']) && !isset($_REQUEST['inputCommunityName']) && !isset($_REQUEST['inputCommunityCity']) && !isset($_REQUEST['inputCommunityState']) && !isset($_REQUEST['inputCommunityCountry'])) {
	exit;
}

include ('db_class.php'); // Include the database class 

// Get the variables and set them
$community_id = $_REQUEST['community'];
$name = $_REQUEST['inputCommunityName'];

if (isset($_REQUEST['inputCommunityDescription'])) {
    $description = $_REQUEST['inputCommunityDescription'];
} else {
    $description = "";
}
if (isset($_REQUEST['inputDefaultPinColor'])) {
    $default_pin_color = $_REQUEST['inputDefaultPinColor'];
} else {
    $default_pin_color = '#96F0F0';
}
if (isset($_REQUEST['inputDefaultPinColorStatus'])) {
    $default_pin_color_status = $_REQUEST['inputDefaultPinColorStatus'];
} else {
    $default_pin_color_status = 1;
}
if (isset($_REQUEST['inputAllowUserPinColors'])) {
    $allow_user_pin_colors = $_REQUEST['inputAllowUserPinColors'];
} else {
    $allow_user_pin_colors = 0;
}

$city = $_REQUEST['inputCommunityCity'];
$state = $_REQUEST['inputCommunityState'];
$country = $_REQUEST['inputCommunityCountry'];

// MySQL sanitize
$name = stripslashes($name);
$description = stripslashes($description);
$default_pin_color = stripslashes($default_pin_color);
$default_pin_color_status = stripslashes($default_pin_color_status);
$allow_user_pin_colors = stripslashes($allow_user_pin_colors);
$city = stripslashes($city);
$state = stripslashes($state);
$country = stripslashes($country);

$name = mysql_real_escape_string($name);
$description = mysql_real_escape_string($description);
$default_pin_color = mysql_real_escape_string($default_pin_color);
$default_pin_color_status = mysql_real_escape_string($default_pin_color_status);
$allow_user_pin_colors = mysql_real_escape_string($allow_user_pin_colors);
$city = mysql_real_escape_string($city);
$state = mysql_real_escape_string($state);
$country = mysql_real_escape_string($country);


$sql_update_community_name = "UPDATE config SET  community_name='$name' WHERE community_id= '$community_id'";

$sql_update_community_description = "UPDATE config SET  community_description='$description' WHERE community_id= '$community_id'";

$sql_update_community_pin_color = "UPDATE config SET  default_pin_color='$default_pin_color' WHERE community_id= '$community_id'";

$sql_update_community_pin_color_status = "UPDATE config SET  default_pin_color_status='$default_pin_color_status' WHERE community_id= '$community_id'";

$sql_update_community_allow_user_pin_colors = "UPDATE config SET  allow_user_pin_colors='$allow_user_pin_colors' WHERE community_id= '$community_id'";

$sql_update_community_city = "UPDATE communities SET city='$city' WHERE community_id= '$community_id'";

$sql_update_community_province = "UPDATE communities SET  province='$state' WHERE community_id= '$community_id'";

$sql_update_community_country = "UPDATE communities SET  country='$country' WHERE community_id= '$community_id'";

if (mysqli_query($conn, $sql_update_community_name) && mysqli_query($conn, $sql_update_community_description) && mysqli_query($conn, $sql_update_community_pin_color) && mysqli_query($conn, $sql_update_community_pin_color_status) && mysqli_query($conn, $sql_update_community_allow_user_pin_colors) && mysqli_query($conn, $sql_update_community_city) && mysqli_query($conn, $sql_update_community_province) && mysqli_query($conn, $sql_update_community_country)) {

    echo "success";
    //header( 'Location: myhome.php' );
} else {
    echo "fail";
}
?>