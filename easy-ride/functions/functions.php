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

function json_respond($status, $msg, $data = NULL) {
    $response = array("status" => $status, "msg" => $msg);
    if ($data) {
        foreach ($data as $key => $value)
            $response[$key] = $value;
    }
    echo json_encode($response);
}



/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitude_From Latitude of start point in [deg decimal]
 * @param float $longitude_From Longitude of start point in [deg decimal]
 * @param float $latitude_To Latitude of target point in [deg decimal]
 * @param float $longitude_To Longitude of target point in [deg decimal]
 * @return float Distance between points in miles
 */

function distance_miles ($point_a, $point_b){

    $earth_Radius = 6371000; // Mean earth radius in meters [m]

    // convert from degrees to radians
  $lat_From = deg2rad($point_a['lat']);
  $lon_From = deg2rad($point_a['lon']);
  $lat_To = deg2rad($point_b['lat']);
  $lon_To = deg2rad($point_b['lon']);

    // Computer lon and lat differences
  $lat_Delta = $lat_To - $lat_From;
  $lon_Delta = $lon_To - $lon_From;

  $angle = 2 * asin(sqrt(pow(sin($lat_Delta / 2), 2) +
            cos($lat_From) * cos($lat_To) * pow(sin($lon_Delta / 2), 2)));

   $distance = $angle * $earth_Radius; // Distance in meters
   $dist_miles = $distance*0.000621371192 ; // Convert meters to miles
   return $dist_miles;
}

?>
