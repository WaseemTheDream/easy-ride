<?php

$appname = 'Easy Ride';

$dbhost = 'localhost';
$dbname = 'easy_ride';
$dbuser = 'easy_ride';
$dbpass = 'rideLikeABaller';
$users_table = 'Users';

$connection = mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname, $connection) or die(mysql_error());
echo "<h1>Database selected.</h1>";

function sanitizeString($var)
{
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}

function queryMysql($query)
{
	$result = mysql_query($query) or die(mysql_error());
	return $result;
	}

function destroySession()
{
	$_SESSION=array();
	if (session_id() != "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-2592000, '/');
		session_destroy();
	}

?>