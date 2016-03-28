<?php
if( !isset($_REQUEST["name"]) && !isset($_REQUEST["city"]) && !isset($_REQUEST["state"]) && !isset($_REQUEST["country"]) ) {
	exit;
}

include("db_class.php");

if( $_REQUEST["name"] != "" && $_REQUEST["city"] != "" && $_REQUEST["state"] != "" && $_REQUEST['country'] != "" ) {
	$name = $_REQUEST["name"];
	$name = stripslashes($name);
	$name = mysql_real_escape_string($name);
	$city = $_REQUEST["city"];
	$city = stripslashes($city);
	$city = mysql_real_escape_string($city);
	$state = $_REQUEST["state"];
	$state = stripslashes($state);
	$state = mysql_real_escape_string($state);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE config.community_name LIKE '%$name%' AND communities.city LIKE '%$city%' AND communities.province LIKE '%$state%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["city"] != "" && $_REQUEST["state"] != "" && $_REQUEST['country'] != "" ) {
	$city = $_REQUEST["city"];
	$city = stripslashes($city);
	$city = mysql_real_escape_string($city);
	$state = $_REQUEST["state"];
	$state = stripslashes($state);
	$state = mysql_real_escape_string($state);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE communities.city LIKE '%$city%' AND communities.province LIKE '%$state%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["name"] != "" && $_REQUEST["state"] != "" && $_REQUEST['country'] != "" ) {
	$name = $_REQUEST["name"];
	$name = stripslashes($name);
	$name = mysql_real_escape_string($name);
	$state = $_REQUEST["state"];
	$state = stripslashes($state);
	$state = mysql_real_escape_string($state);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE config.community_name LIKE '%$name%' AND communities.province LIKE '%$state%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["name"] != "" && $_REQUEST["city"] != "" && $_REQUEST['country'] != "" ) {
	$name = $_REQUEST["name"];
	$name = stripslashes($name);
	$name = mysql_real_escape_string($name);
	$city = $_REQUEST["city"];
	$city = stripslashes($city);
	$city = mysql_real_escape_string($city);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE config.community_name LIKE '%$name%' AND communities.city LIKE '%$city%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["name"] != "" && $_REQUEST["country"] != "" ) {
	$name = $_REQUEST["name"];
	$name = stripslashes($name);
	$name = mysql_real_escape_string($name);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE config.community_name LIKE '%$name%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["city"] != "" && $_REQUEST["country"] != "" ) {
	$city = $_REQUEST["city"];
	$city = stripslashes($city);
	$city = mysql_real_escape_string($city);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE communities.city LIKE '%$city%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["state"] != "" && $_REQUEST['country'] != "") {
	$state = $_REQUEST["state"];
	$state = stripslashes($state);
	$state = mysql_real_escape_string($state);
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE communities.province LIKE '%$state%' AND communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}
else if( $_REQUEST["country"] != "" ) {
	$country = $_REQUEST["country"];
	$country = stripslashes($country);
	$country = mysql_real_escape_string($country);
	$sql_community_search = "SELECT * FROM config INNER JOIN communities ON config.community_id = communities.community_id WHERE communities.country LIKE '%$country%'";
	$community_search_result = mysqli_query($conn, $sql_community_search);
}

?>

<h1> Search Results </h1> <hr>
<table class='table table-borderless'>

<?php
if (!isset($community_search_result)) {
	print "There were no results for your search.";
} elseif (!mysqli_fetch_row($community_search_result)) {
	print "There were no results for your search.";
} 
else {
	$community_search_result = mysqli_query($conn, $sql_community_search);
	while($row = mysqli_fetch_assoc($community_search_result)){
		print "<tr> <th> Community Name </th> <th> City </th> <th> State / Province </th> <th> Country </th> </tr> ";
		print "<tr> <td style='color: #317eac'>" . $row['community_name'] . "</td> <td> ". $row['city'] . "</td> <td> ". $row['province'] . "</td> <td> ". $row['country'] . "</td> </tr>";
		print "<tr> <td colspan='4'> <b> Description: </b> <br /> ". $row['community_description'] . "</td></tr>";
		print "<tr> <td> <button type='button' class='btn btn-success btn-md' style='width:auto' onclick='load_map_into_modal(`" . $row['community_id'] ."`)' data-toggle='modal' data-target='#view_community_modal'>View Community</button> </td>
				<td> <button type='button' class='btn btn-info btn-md' style='width:auto' onclick='show_join_community_modal(`" . $row['community_id'] ."`)' data-toggle='modal'>Join Community</button> </td> <td> </td> <td> </td></tr>";
	}
}

?>

</table>