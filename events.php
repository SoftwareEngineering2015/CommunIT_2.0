<!DOCTYPE html>
<html>
<head>
	<?php
require_once( "template_class.php");       // css and headers
$H = new template( "Directory" );
$H->show_template( );
?>

<?php
// Create connection
$P = new manage_db;
$P->connect_db();
//Gets the information of a residence and it's head resident 
$sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, address, latitude, longitude, emergency_contact, phone_one, email_address FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL ORDER BY last_name ";
$P->do_query($sqlResidences);
$resultResidences = mysql_query($sqlResidences);    
 // $row = mysql_fetch_assoc($resultResidences)


?>

</head>

<body>


<div class="col-xs-10 col-xs-offset-1" style=" height:100%;">
	<h3> Community Events </h3>
	<div class='col-xs-8'>
		<table class='table table-hover'>
			<tbody>
				<tr>
					<th> Event Name </th>
					<th> Event Day </th>
					<th> Event Time </th>
					<th> Event Location </th>
					<th> Created By </th>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>