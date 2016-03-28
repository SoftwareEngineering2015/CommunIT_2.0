<?php
sleep(5);
if( !isset($_REQUEST["city"]) && !isset($_REQUEST["state"]) && !isset($_REQUEST["country"]) ) {
	exit;
}

$server = '127.0.0.1';
$user = 'root';
$pass = '';
$mydb = 'capstone';
$connection = new mysqli($server, $user, $pass, $mydb );

if( $_REQUEST["city"] != "" ) {
	$city = $_REQUEST["city"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.city LIKE '%$city%'";
	$community_search_result = $connection->query($sql_community_search);
}
if( $_REQUEST["state"] != "" ) {
	$state = $_REQUEST["state"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country  FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.state LIKE '%$state%'";
	$community_search_result = $connection->query($sql_community_search);
}
if( $_REQUEST["country"] != "" ) {
	$country = $_REQUEST["country"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country  FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.country LIKE '%$country%'";
	$community_search_result = $connection->query($sql_community_search);
}
if( $_REQUEST["city"] != "" && $_REQUEST["state"] != "" ) {
	$city = $_REQUEST["city"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country  FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.city LIKE '%$city%' AND communities.state LIKE '%$state%'";
	$community_search_result = $connection->query($sql_community_search);
}
if( $_REQUEST["city"] && $_REQUEST["country"] != "" ) {
	$city = $_REQUEST["city"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country  FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.city LIKE '%$city%' AND communities.country LIKE '%$country%'";
	$community_search_result = $connection->query($sql_community_search);
}
if( $_REQUEST["state"] != "" && $_REQUEST['country'] != "") {
	$state = $_REQUEST["state"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country  FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.state LIKE '%$state%' AND communities.country LIKE '%$country%'";
	$community_search_result = $connection->query($sql_community_search);
}
if( $_REQUEST["city"] != "" && $_REQUEST["state"] != "" && $_REQUEST['country'] != "" ) {
	$city = $_REQUEST["city"];
	$sql_community_search = "SELECT community_name, community_description, city, state, country  FROM config INNER JOIN communities ON config.community_id = communities.id WHERE communities.city LIKE '%$city%' AND communities.state LIKE '%$state%' AND communities.country LIKE '%$country%'";
	$community_search_result = $connection->query($sql_community_search);
}

?>

<h1> Search Results </h1> <hr>
<table class='table table-borderless'>
<?php
if (!isset($community_search_result)) {
	print "There were no results for your search.";
} elseif ($community_search_result->num_rows === 0) {
	print "There were no results for your search.";
} 
else {
	while($row = $community_search_result->fetch_assoc()){
		print "<tr> <th> Community Name </th> <th> City </th> <th> State </th> <th> Country </th> </tr> ";
		print "<tr> <td style='color: #317eac'>" . $row['community_name'] . "</td> <td> ". $row['city'] . "</td> <td> ". $row['state'] . "</td> <td> ". $row['country'] . "</td> </tr>";
		print "<tr> <td colspan='4'> <b> Description: </b> <br /> ". $row['community_description'] . "</td></tr>";
		print "<tr><td colspan='4'> <button type='button' class='btn btn-success btn-md' style='width:auto' onclick='load_map_into_modal()' data-toggle='modal' data-target='#view_community_modal'>View Community</button> </td></tr>";
	}
}

/* close connection */
$connection->close();

?>
</table>