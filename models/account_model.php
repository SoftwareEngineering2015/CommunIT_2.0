<?php
//Bring the db_class into this file.
require_once 'db_class.php';

//Here we are grabbing json encoded object form profileController.js.
//Note: We are getting the user_id here and nothing else.
$postdata = file_get_contents("php://input");
   //Once we get the data we have to decode it.
   $request = json_decode($postdata);
   @$user = $request->user;

$checkQuery = "SELECT password, email FROM users WHERE user_id = '$user' LIMIT 1";
$resultCheck = $conn->query($checkQuery) or exit("Error code ({$conn->errno}): {$conn->error}");


if (mysqli_fetch_row($resultCheck)) {

        //Here we build the query. We use $user from above to grab all profile infomation for each marker they have.
        $query = "SELECT password, email FROM users WHERE user_id = '$user' LIMIT 1";
        //Do the query.
        $results = mysqli_query($conn, $query);

    if (mysqli_fetch_row($results)) {

        //Do the query.
        $results = mysqli_query($conn, $query);

        //Now we use mysqli_fetch_assoc to cleanly lay-out the result so we can send it over
        //to the controller.
        while($row = mysqli_fetch_assoc($results)) {
                $json_account_array =  array(
                               "password" => $row['password'],
                               "email" => $row['email']
                             );
         }

         //Here we encode our "json object" and send itto profileController.js.
  }else{
    $json_account_array = array (
        "error" => "No account for this account"
    );
  }
} else {
    $json_account_array = array (
        "error" => "No account for this account"
    );
}

 header('content-type: application/json');
 echo json_encode($json_account_array, JSON_PRETTY_PRINT);

?>
