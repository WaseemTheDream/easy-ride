<html lang='en'>
<body>
<?php

include_once "functions.php";

$connection = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $connection);

// Create  table
$create_users = "CREATE TABLE user_accounts
(
	userID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (userID),
	firstName varchar(64) NOT NULL,
	lastName varchar(64) NOT NULL,
	emailAddress varchar(128) NOT NULL,
	driversLicenseID varchar(64),
	gender binary(1) NOT NULL,
	password varchar(32) NOT NULL
)";


if (!queryMySql($create_users)) {
	die ('Error: ' . mysql_error());
} else {
	echo "Successfully created tables.";
}

mysql_close($connection);

?>
</body>
</html>