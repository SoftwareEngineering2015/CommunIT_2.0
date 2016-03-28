
<?php
include('db_class.php');

$P = new manage_db;
$P->connect_db();

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

//Puts hyphens in the hexcode for user readability only
//Does not affect the database
function hyphen_hexcode($hexID) {
    $first = substr($hexID, 0, 4);
    $second   = substr($hexID, 4, 4);
    $third   = substr($hexID, 8, 4);
    
    return "$first-$second-$third";
}

//Error counter, if it reaches 100 the code stops.
$error_counter =0;

//Loop that interacts with the database
do {
    $available = true;
    $hexID = generate_HexCode();
    
    $sql_hexcode_check = "SELECT hexcode FROM hexcodes WHERE hexcode= '$hexID' LIMIT 1";
    $result_hexcode_check = mysqli_query($conn, $sql_hexcode_check); 

    //Check to see if the HexCode Already Exists
    if(mysqli_fetch_row($result_hexcode_check)){
            $available = false;
            $error_counter++;
        }else{
            $available = true;
            $sql_hexcode_insert = "INSERT INTO hexcodes (hexcode) VALUES ('$hexID')";
            $result_hexcode_insert = mysqli_query($conn, $sql_hexcode_insert); 
            echo "Inserted: " . hyphen_hexcode($hexID);
        }
    if($error_counter == 100){
        echo "Problem with connection, please try again.";
    }
}while($available == false && $error_counter < 100);
    
//TEMPORARY CODE USED FOR TESTING
//Selects all the hexcodes and sorts the in Ascending order.
$sql_get_hexcodes = "SELECT hexcode FROM hexcodes ORDER BY hexcode ASC";
$result_get_hexcodes = mysqli_query($conn, $sql_get_hexcodes);

if( $result_get_hexcodes ){
    // success! check results
    while($row = mysqli_fetch_assoc($result_get_hexcodes)) {
        $hexID = $row["hexcode"];
    }

}else{
    print("Failed to select hexcodes.");
}





?>