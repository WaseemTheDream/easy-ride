<html lang='en'>
<body>
<?php
require_once "functions.php";
require_once "user.php";

$connection = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $connection);

// Create the trip table
$Create_Table1 = "CREATE TABLE trip
(
	TripID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (TripID),
	AddressFrom varchar(64) NOT NULL,
	AddressTo varchar(64) NOT NULL,
	TripDate int NOT NULL,
	TripLength varchar(128) NOT NULL,
	WomenOnly binary(1) NOT NULL,
	Spots varchar(128) NOT NULL,
	Message varchar(160) NOT NULL,
	DriverID int NOT NULL
)";


// Create Coordinates Table

$Create_Table2 = "CREATE TABLE coordinates_Info
(
	DriverID int NOT NULL,
	TripDate int NOT NULL,
	PRIMARY KEY (DriverID,TripDate),
	LatitudeFrom FLOAT( 14) NOT NULL,
	LongitudeFrom FLOAT( 14 ) NOT NULL,
	LatitudeTo FLOAT( 14) NOT NULL,
	LongitudeTo FLOAT( 14 ) NOT NULL
)
";

$Create_Table3 = "CREATE TABLE tripaddress
(
	addressID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (addressID),
	Address varchar(64) NOT NULL,
	TripDate int NOT NULL,
	DriverID int NOT NULL
)";

// if (!queryMySql($create_users)) {
// 	die ('Error: ' . mysql_error());
// } else {
// 	echo "Successfully created tables.";
// }

// if (!queryMysql($Create_Table1)&!queryMysql($Create_Table2)&!queryMysql($Create_Table3)) {
// 	die ('Error: ' . mysql_error());
// } else {
// 	echo "Successfully created tables.";
// }

if (!mysql_query($create_user_table))
	die ("Failed to create user table: " . mysql_error());
else
	echo "Successfully created $user_table table.";

mysql_close($connection);

?>
</body>
</html>