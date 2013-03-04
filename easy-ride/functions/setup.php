<html lang='en'>
<body>
<?php
require_once "functions.php";
require_once "user.php";

$connection = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $connection);

if (!mysql_query("CREATE TABLE IF NOT EXISTS " . $user_table_definition))
	die ("Failed to create user table: " . mysql_error());
else
	echo "Created table ".USER_TABLE." if it didn't exist.";

mysql_close($connection);

?>
</body>
</html>