<?php

// Make sure all the required variables are set, if not then exit
if (!isset($_REQUEST['inputUsername']) && !isset($_REQUEST['inputEmail']) && !isset($_REQUEST['inputFirstName']) && !isset($_REQUEST['inputLastName']) && !isset($_REQUEST['inputBirthDate']) && !isset($_REQUEST['inputPassword'])) {
	exit;
}

include ('db_class.php'); // Include the database class
 
// Get the variables and set them
$username = $_REQUEST['inputUsername'];
$email = $_REQUEST['inputEmail'];
$firstName = $_REQUEST['inputFirstName'];
if (isset($_REQUEST['inputMiddleInitial'])) {
    $middleInitial = $_REQUEST['inputMiddleInitial'];
} else {
    $middleInitial = "";
}
$lastName = $_REQUEST['inputLastName'];
if (isset($_REQUEST['inputGender'])) {
    $gender = $_REQUEST['inputGender'];
} else {
    $gender = "";
}
$birthDate = $_REQUEST['inputBirthDate'];
$password = $_REQUEST['inputPassword'];

// MySQL sanitize
$username = stripslashes($username);
$email = stripslashes($email);
$firstName = stripslashes($firstName);
$middleInitial = stripslashes($middleInitial);
$lastName = stripslashes($lastName);
$gender = stripslashes($gender);
$birthDate = stripslashes($birthDate);
$password = stripslashes($password);

$username = mysql_real_escape_string($username);
$email = mysql_real_escape_string($email);
$firstName = mysql_real_escape_string($firstName);
$middleInitial = mysql_real_escape_string($middleInitial);
$lastName = mysql_real_escape_string($lastName);
$gender = mysql_real_escape_string($gender);
$birthDate = mysql_real_escape_string($birthDate);
$password = mysql_real_escape_string($password);

// Check to see if the username already exists
$sql_username_check = "SELECT username FROM users WHERE username= '$username' LIMIT 1";
$result_username_check = mysqli_query($conn, $sql_username_check);

if(mysqli_fetch_row($result_username_check)) {
	echo "usernameExists"; exit;
}

// Check to see if the email already exists
$sql_email_check = "SELECT email FROM users WHERE email= '$email' LIMIT 1";
$result_email_check = mysqli_query($conn, $sql_email_check);

if(mysqli_fetch_row($result_email_check)) {
	echo "emailExists"; exit;
}

// If the username and email are free begin to create the user
// Loop that interacts with the database to insert the user
$error_counter = 0;
do {
    $available = true;
    $user_id = generate_HexCode();

    $sql_user_id_check = "SELECT user_id FROM users WHERE user_id= '$user_id' LIMIT 1";
    $result_user_id_check = mysqli_query($conn, $sql_user_id_check);

    //Check to see if the User ID Already Exists
    if(mysqli_fetch_row($result_user_id_check)){
            $available = false;
            $error_counter++;
        }else{
            $available = true;
            $sql_create_user = "INSERT INTO users (user_id, username, password, email, first_name, last_name, m_initial, gender, birth_date, date_created) VALUES ('$user_id', '$username', '$password', '$email', '$firstName', '$lastName', '$middleInitial', '$gender', '$birthDate',  NOW())";
            if(mysqli_query($conn, $sql_create_user)) {
                echo "success"; exit();
            }
            // Close the connection down here somewhere
        }
    if($error_counter == 100){
        echo "Problem with connection, please try again.";
    }
}while($available == false && $error_counter < 100);

//Function to generate Hex Code
function generate_HexCode() {
    //Length of Hex Code
    $hexIDLength = 12;
    //Characters to use in Hex Code
    $hexOptions = 'ABCDEF1234567890';
    $hexID = '';
    //Assigns the
    $optionsLength = (strlen($hexOptions) - 1);
    //Loops through and randomly creates a hex string
    for ($i = 0; $i < $hexIDLength; $i++) {
        $n = mt_rand(0, $optionsLength);
        $hexID = $hexID . $hexOptions[$n];
    }
    return $hexID;
}

?>
