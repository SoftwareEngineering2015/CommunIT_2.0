<?php
if( !isset($_REQUEST["name"]) && !isset($_REQUEST["city"]) && !isset($_REQUEST["state"]) && !isset($_REQUEST["country"]) && !isset($_REQUEST["can_join"])) {
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

$json_array = array();
$counter = 0;

if (!isset($community_search_result)) {
	$json_array[$counter]["status"] = "error";
	$json_array[$counter]["message"] = "There were no results for your search.";
	echo json_encode($json_array); exit();
} elseif (!mysqli_fetch_row($community_search_result)) {
	$json_array[$counter]["status"] = "error";
	$json_array[$counter]["message"] = "There were no results for your search.";
	echo json_encode($json_array); exit();
} 
else {
	$community_search_result = mysqli_query($conn, $sql_community_search);
	if (mysqli_num_rows($community_search_result) == 0) {
		$json_array[$counter]["status"] = "error";
		$json_array[$counter]["message"] = "There were no results for your search.";
		echo json_encode($json_array); exit();
	} else {
		while($row = mysqli_fetch_assoc($community_search_result)){
			$json_array[$counter]["status"] = "success";
			$json_array[$counter]["community_id"] = $row['community_id'];
			$json_array[$counter]["community_name"] = $row['community_name'];
			$json_array[$counter]["community_description"] = $row['community_description'];
			$json_array[$counter]["city"] = $row['city'];
			$json_array[$counter]["state"] = $row['province'];
			$json_array[$counter]["country"] = $row['country'];

			$counter++;
		}
		echo json_encode($json_array); exit();
	}
}

?>