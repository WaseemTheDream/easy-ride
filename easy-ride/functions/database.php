<?php
namespace database;
require_once 'functions.php';
use functions;

define("TRIP_TABLE", 'trip');
define("PLACE_TABLE", 'place');

// Trip Table Definition
$trip_table_definition = TRIP_TABLE."
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    driver_id INT UNSIGNED NOT NULL,
    spots TINYINT NOT NULL,
    length VARCHAR(128) NOT NULL,
    message VARCHAR(4096),
    women_only BINARY(1) NOT NULL,
    departure_time INT NOT NULL,
    origin_id INT NOT NULL,
    destination_id INT NOT NULL
)";

$place_table_definition = PLACE_TABLE."
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(128),
    lat FLOAT NOT NULL,
    lon FLOAT NOT NULL
)";

/**
 * Adds the specified trip to the database.
 * @param data associative array containing all of the trip data.
 * @return id the id of the inserted trip, NULL if there was an error
 */
function add_trip($data)
{
    $driver_id = functions\sanitize_string($data['driver_id']);
    $spots = functions\sanitize_string($data['spots']);
    $length = functions\sanitize_string($data['length']);
    $message = functions\sanitize_string($data['message']);
    $women_only = functions\sanitize_string($data['women_only']);
    $departure_time = functions\sanitize_string($data['departure_time']);
    $origin_id = functions\sanitize_string($data['origin_id']);
    $destination_id = functions\sanitize_string($data['destination_id']);

    $query = "INSERT INTO ".TRIP_TABLE." (
            driver_id,
            spots,
            length,
            message,
            women_only,
            departure_time,
            origin_id,
            destination_id
        ) VALUES (
            '$driver_id',
            '$spots',
            '$length',
            '$message',
            '$women_only',
            '$departure_time',
            '$origin_id',
            '$destination_id')";

    if (!mysql_query($query)) {
        echo "Failed to add place: " . mysql_error();
        return NULL;
    }
    return mysql_insert_id();
}

/**
 * Gets the specified trip.
 * @param id the id of the trip.
 * @param row the processed trip row in the database if found, NULL otherwise.
 */
function get_trip($id)
{
    $s_id = functions\sanitize_string($id);
    $query = "SELECT * FROM ".TRIP_TABLE." WHERE id='$s_id'";
    $result = mysql_query($query);
    if (!$result) return NULL;
    elseif (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        return process_trip_row($row);
    }
    return NULL;
}

function get_all_trips()
{
    $query = "SELECT * FROM ".TRIP_TABLE;
    $result = mysql_query($query);
    $rows = array();
    if (!$result) return NULL;
    $num_rows = mysql_num_rows($result);
    for ($i = 0; $i < $num_rows; ++$i) {
        $row = mysql_fetch_assoc($result);
        $rows[] = process_trip_row($row);
    }
    return $rows;
}

function process_trip_row($row)
{
    $row['origin'] = get_place($row['origin_id']);
    $row['destination'] = get_place($row['destination_id']);
    return $row;
}

/**
 * Adds the specified place to the database.
 * @param data associative array containing all of the place data.
 * @return id the id of the inserted place, NULL if there was an error.
 */
function add_place($data)
{
    $address =  functions\sanitize_string($data['address']);
    $lat = functions\sanitize_string($data['lat']);
    $lon = functions\sanitize_string($data['lon']);

    $query = "INSERT INTO ".PLACE_TABLE." (
            address,
            lat,
            lon 
        ) VALUES (
            '$address',
            '$lat',
            '$lon')";

    if (!mysql_query($query)) {
        echo "Failed to add place: " . mysql_error();
        return NULL;
    }
    return mysql_insert_id();
}

/**
 * Gets the place specified by id.
 * @param id the row id of the place.
 * @return row the place row in the database if found, NULL otherwise.
 */
function get_place($id)
{
    $s_id = functions\sanitize_string($id);
    $query = "SELECT * FROM ".PLACE_TABLE." WHERE id='$s_id'";
    $result = mysql_query($query);
    if (!$result) return NULL;
    elseif (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        return $row;
    }
    return NULL;
}

/**
  * Returns all the trips near the given route
  * TODO: Sanitize input
  * @param route the route for which to find nearby trips
  * @return an array of all the trips nearby the route
  */
function get_trips_near($route) {

    $threshold = 0.25; // The Threshold
    $query_origin_lat = $route['origin']['lat'];
    $query_origin_lon = $route['origin']['lon'];
    $destination_origin_lat = $route['destination']['lat'];
    $destination_origin_lon = $route['destination']['lon'];
    $trip_table = TRIP_TABLE;
    $place_table = PLACE_TABLE;

    $search_query =
        "SELECT  $trip_table.*
            FROM    $trip_table, $place_table
            INNER JOIN $trip_table on $trip_table.origin_id = $place_table.id
            WHERE  
                    $place_table.lat - $threshold <= $query_origin_lat
                AND $query_origin_lat <=  $place_table.lat + $threshold 
                AND $place_table.lon - $threshold <= $query_origin_lon 
                AND $query_origin_lon <=  $place_table.lon + $threshold
                       
        INTERSECT

        SELECT  $trip_table.*
            FROM    $trip_table, $place_table
            INNER JOIN $trip_table on $trip_table.destination_id = $place_table.id
            WHERE
                    $place_table.lat - $threshold <= $destination_origin_lat  
                AND $destination_origin_lat <=  $place_table.lat + $threshold
                AND $place_table.lon - $threshold <= $destination_origin_lon
                AND $destination_origin_lon <=  $place_table.lon + $threshold";

    $result = mysql_query($search_query);
    $rows = array();

    if (!$result) return NULL;
    $num_rows = mysql_num_rows($result);

    for ($i = 0; $i < $num_rows; ++$i) {
            $row = mysql_fetch_assoc($result);
            $rows[] = process_trip_row($row);
    }
    return $rows;
}