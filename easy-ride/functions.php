<?php

$dbhost = 'localhost';
$dbname = 'easy_ride';
$dbuser = 'root';
$dbpass = '';
$appname = 'Easy Ride';

mysql_connect(localhost, root,'') or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

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