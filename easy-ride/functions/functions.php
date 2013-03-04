<?php

$appname = 'Easy Ride';
$dbhost = 'localhost';
$dbname = 'easy_ride';
$dbuser = 'easy_ride';
$dbpass = 'rideLikeABaller';

$connection = mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname, $connection) or die(mysql_error());

function sanitize_string($var)
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

function postStatusRespond($status, $msg)
{
	$response = array("status" => $status, "msg" => $msg);
	echo json_encode($response);
}


?>
