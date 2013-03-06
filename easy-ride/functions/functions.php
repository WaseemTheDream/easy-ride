<?php
namespace functions;

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
    $var = trim($var);
    return mysql_real_escape_string($var);
}

function html_respond($status, $msg) {
    $string = "<h1 style='text-align: center;'>$status</h1>";
    $string .= "<p style='text-align: center;'>$msg</p>";
    $string .= "<p style='text-align: center;'><a href='/index.php'>Click here to continue</a></p>";
    echo $string;
}

function json_respond($status, $msg, $log = NULL) {
    $response = array("status" => $status, "msg" => $msg);
    if ($log) {
        $response["log"] = $log;
    }
    echo json_encode($response);
}


?>
