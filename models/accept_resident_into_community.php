<?php

// Get the community id from the post for which community map to display
if (isset($_REQUEST["community"]) && isset($_REQUEST["user"])) {
    $community_id = $_REQUEST["community"];
    $user_id      = $_REQUEST["user"];
} else {
    echo "noCommunity";
    exit;
}

include("db_class.php");

$sql_get_user_information        = "SELECT * FROM users WHERE user_id = '$user_id'";
$sql_get_user_information_result = mysqli_query($conn, $sql_get_user_information);

while ($row = $sql_get_user_information_result->fetch_assoc()) {
    // Loop that interacts with the database to insert the user
    $error_counter = 0;
    do {
        $available  = true;
        $profile_id = generate_HexCode();
        
        $sql_profile_id_check    = "SELECT profile_id FROM profiles WHERE profile_id= '$profile_id' LIMIT 1";
        $result_profile_id_check = mysqli_query($conn, $sql_profile_id_check);
        
        //Check to see if the User ID Already Exists
        if (mysqli_fetch_row($result_profile_id_check)) {
            $available = false;
            $error_counter++;
        } else {
            $available             = true;
            $sql_give_user_profile = "INSERT INTO profiles (profile_id, user_id, community_id) VALUES ('$profile_id', '$user_id', '$community_id')";
            
            $sql_accpet_user_into_community = "INSERT INTO users_to_communities (user_id, community_id, privilege_id) VALUES ('$user_id', '$community_id', '3')";
            
            $sql_delete_request = "DELETE FROM requests_to_join_communities WHERE user_id = '$user_id' AND community_id = '$community_id'";
            
            if (mysqli_query($conn, $sql_give_user_profile) && mysqli_query($conn, $sql_accpet_user_into_community) && mysqli_query($conn, $sql_delete_request)) {
                echo "success";
                exit();
            }
            // Close the connection down here somewhere
        }
        if ($error_counter == 100) {
            echo "Problem with connection, please try again.";
        }
    } while ($available == false && $error_counter < 100);
    
}




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