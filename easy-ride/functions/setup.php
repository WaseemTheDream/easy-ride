<html lang='en'>
<body>
<?php
require_once "functions.php";
require_once "user.php";
require_once "database.php";

$connection = mysql_connect(
    functions\$dbhost,
    functions\$dbuser,
    functions\$dbpass);
mysql_select_db(functions\$dbname, functions\$connection);

$tables = array(
	USER_TABLE => user\$user_table_definition,
	TRIP_TABLE => database\$trip_table_definition,
	PLACE_TABLE => database\$place_table_definition);

foreach ($tables as $name => $definition) {
	if (!mysql_query("CREATE TABLE IF NOT EXISTS $definition"))
		die ("Failed to create table: $name.<br>");
	else
		echo "Created table $name if it didn't exist.<br>";
}

mysql_close($connection);

?>
</body>
</html>