<?php
include('db_class.php');

$P = new manage_db;
$P->connect_db();

function generate_HexCode() {
    $hexIDLength = 12;
    $hexOptions = 'ABCDEF1234567890';
    $hexID = ''; 
    $optionsLength = (strlen($hexOptions) - 1);
    for ($i = 0; $i < $hexIDLength; $i++) {
        $n = mt_rand(0, $optionsLength);
        $hexID = $hexID . $hexOptions[$n];
    }
    return $hexID;
}


function hyphen_hexcode($hexID) {
    $first = substr($hexID, 0, 4);
    $second   = substr($hexID, 4, 4);
    $third   = substr($hexID, 8, 4);
    
    return "$first-$second-$third";
}


//Create Array, pushes hex_ids into array 
//uses the array to count unique hex_ids
$hex_array = array();
for ( $i = 0; $i < 100000; $i++){
    $hexID = generate_HexCode();
    $hex_array[] = $hexID;

$error_counter =0;
/*
do {
    //$available = true;
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
            echo "Inserted: " . hyphen_hexcode($hexID) . "<br />";
        }
    if($error_counter == 100){
        echo "Problem with connection, please try again.";
    }
}while($available == false && $error_counter < 100);
*/   
}

$uniqueCount = array_count_values($hex_array);
echo '<br />No. of NON Duplicate Items: '.count($uniqueCount).'<br><br>';
print_r($uniqueCount);
echo("<br />");
/*
echo "<br/><h1>List of Hex Codes</h1><table border='1' style='border-collapse: collapse; background-color: #e5f2ff;'>";

$sql_get_hexcodes = "SELECT hexcode FROM hexcodes ORDER BY hexcode ASC";
$result_get_hexcodes = mysqli_query($conn, $sql_get_hexcodes);

if( $result_get_hexcodes ){
    // Yay it works!
    while($row = mysqli_fetch_assoc($result_get_hexcodes)) {
        $hexID = $row["hexcode"];
        echo ("<tr><td>". hyphen_hexcode($hexID) ."</td></tr>");
    }

}else{
    print("Failed to select hexcodes.");
}

    
echo "</table>";
*/



?>