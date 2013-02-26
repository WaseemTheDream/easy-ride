<?php

$appname = 'Easy Ride';

$dbhost = 'localhost';
$dbname = 'easy_ride';
$dbuser = 'easy_ride';
$dbpass = 'Rs3ewSd20';			/* Password FOr everyone: rideLikeABaller */
$users_table = 'users'; 
$Trip_Table = 'trip';
$CoordinatesTable = 'coordinates_Info';
$addressTable = 'tripaddress';


$connection = mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname, $connection) or die(mysql_error());

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