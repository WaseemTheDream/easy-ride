<html lang='en'>
	<?php 
	include 'head.php'; 
	include 'navbar.php'; 
	include_once 'functions.php';
	?>
 	
	<script src="js/register.js"></script>
	<body>

<?php

 
// Create the trip table
$Create_Table1 = "CREATE TABLE Trip
(
	TripID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (TripID),
	AddressFrom varchar(64) NOT NULL,
	AddressTo varchar(64) NOT NULL,
	DepDate varchar(64) NOT NULL,
	DepartureTime varchar(128) NOT NULL,
	TripLength varchar(128) NOT NULL,
	WomenOnly binary(1) NOT NULL,
	Spots int NOT NULL,
	Message varchar(160) NOT NULL,
	DriverID int NOT NULL
)";


// Create Coordinates Table

$Create_Table2 = "CREATE TABLE Coordinates_Info
(
	DriverID int NOT NULL,
	DepartureTime varchar(128) NOT NULL,
	DepartureDate varchar(64) NOT NULL,
	PRIMARY KEY (DriverID,DepartureDate,DepartureTime),
	LatitudeFrom FLOAT( 14) NOT NULL,
	LongitudeFrom FLOAT( 14 ) NOT NULL,
	LatitudeTo FLOAT( 14) NOT NULL,
	LongitudeTo FLOAT( 14 ) NOT NULL
);
";

if (!queryMysql($Create_Table1)&!queryMysql($Create_Table2)) {
	die ('Error: ' . mysql_error());
} else {
	echo "Successfully created tables.";
}

mysql_close($connection);

?>
	</body>
</html>