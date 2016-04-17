<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['inputUser']) && !isset($_REQUEST['inputCommunityName']) && !isset($_REQUEST['inputCommunityDescription']) && !isset($_REQUEST['inputCommunityCity']) && !isset($_REQUEST['inputCommunityState']) && !isset($_REQUEST['inputCommunityCountry'])) {
    exit;
}

include('db_class.php'); // Include the database class 

// Get the variables and set them
$user_id     = $_REQUEST['inputUser'];
$name        = $_REQUEST['inputCommunityName'];
$description = $_REQUEST['inputCommunityDescription'];

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

$city    = $_REQUEST['inputCommunityCity'];
$state   = $_REQUEST['inputCommunityState'];
$country = $_REQUEST['inputCommunityCountry'];

// MySQL sanitize
$user_id                  = stripslashes($user_id);
$name                     = stripslashes($name);
$description              = stripslashes($description);
$default_pin_color        = stripslashes($default_pin_color);
$default_pin_color_status = stripslashes($default_pin_color_status);
$allow_user_pin_colors    = stripslashes($allow_user_pin_colors);
$city                     = stripslashes($city);
$state                    = stripslashes($state);
$country                  = stripslashes($country);

$user_id                  = mysql_real_escape_string($user_id);
$name                     = mysql_real_escape_string($name);
$description              = mysql_real_escape_string($description);
$default_pin_color        = mysql_real_escape_string($default_pin_color);
$default_pin_color_status = mysql_real_escape_string($default_pin_color_status);
$allow_user_pin_colors    = mysql_real_escape_string($allow_user_pin_colors);
$city                     = mysql_real_escape_string($city);
$state                    = mysql_real_escape_string($state);
$country                  = mysql_real_escape_string($country);

// Loop that interacts with the database to insert the user
$error_counter = 0;
do {
    $available    = true;
    $community_id = generate_HexCode();
    
    $sql_community_id_check    = "SELECT community_id FROM communities WHERE community_id= '$community_id' LIMIT 1";
    $result_community_id_check = mysqli_query($conn, $sql_community_id_check);
    
    //Check to see if the User ID Already Exists
    if (mysqli_fetch_row($result_community_id_check)) {
        $available = false;
        $error_counter++;
    } else {
        $available = true;
        
        // Loop that interacts with the database to insert the user
        $error_counter_2 = 0;
        do {
            $available_2    = true;
            $profile_id = generate_HexCode();
            
            $sql_profile_id_check    = "SELECT profile_id FROM profiles WHERE profile_id= '$profile_id' LIMIT 1";
            $result_sql_profile_id_check = mysqli_query($conn, $sql_profile_id_check);
            
            //Check to see if the User ID Already Exists
            if (mysqli_fetch_row($result_sql_profile_id_check)) {
                $available_2 = false;
                $error_counter_2++;
            } else {
                $available_2 = true;
                
                $sql_create_community         = "INSERT INTO communities (community_id, city, province, country, date_created) VALUES ('$community_id', '$city', '$state', '$country', NOW())";
                $sql_create_community_config  = "INSERT INTO config (community_id, community_name, community_description, default_pin_color, default_pin_color_status, allow_user_pin_colors) VALUES ('$community_id', '$name', '$description', '$default_pin_color', '$default_pin_color_status', '$allow_user_pin_colors')";
                $sql_create_user_to_community = "INSERT INTO users_to_communities (user_id, community_id, privilege_id) VALUES ('$user_id', '$community_id', '1')";
                
                $sql_give_owner_profile = "INSERT INTO profiles (profile_id, user_id, community_id, has_edited) VALUES ('$profile_id', '$user_id', '$community_id', '1')";
            
                if (mysqli_query($conn, $sql_create_community) && mysqli_query($conn, $sql_create_community_config) && mysqli_query($conn, $sql_create_user_to_community) && mysqli_query($conn, $sql_give_owner_profile)) {
                    echo "success";
                    exit();
                }
                // Close the connection down here somewhere
            }
            if ($error_counter_2 == 100) {
                echo "Problem with connection, please try again.";
            }
        } while ($available_2 == false && $error_counter_2 < 100);
    }
    if ($error_counter == 100) {
        echo "Problem with connection, please try again.";
    }
} while ($available == false && $error_counter < 100);

//Function to generate Hex Code
function generate_HexCode()
{
    //Length of Hex Code
    $hexIDLength   = 12;
    //Characters to use in Hex Code
    $hexOptions    = 'ABCDEF1234567890';
    $hexID         = '';
    //Assigns the 
    $optionsLength = (strlen($hexOptions) - 1);
    //Loops through and randomly creates a hex string
    for ($i = 0; $i < $hexIDLength; $i++) {
        $n     = mt_rand(0, $optionsLength);
        $hexID = $hexID . $hexOptions[$n];
    }
    return $hexID;
}

?>