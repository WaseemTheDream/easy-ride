<?php
namespace database;
require_once 'functions.php';
require_once 'user.php';
use functions;
use user;

define("TRIP_TABLE", 'trip');
define("PLACE_TABLE", 'place');
define("TRIP_REQUEST_TABLE",'trip_request');

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

// Place Table Definition
$place_table_definition = PLACE_TABLE."
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(128),
    lat FLOAT NOT NULL,
    lon FLOAT NOT NULL
)";

// Trip Request Table Definition
$trip_request_table_definition = TRIP_REQUEST_TABLE."
(
    trip_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (trip_id, user_id),
    message VARCHAR(4096),
    status TINYINT DEFAULT 0,
    CONSTRAINT chk_status CHECK (-1 <= status AND status <= 1)
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
    $row['driver'] = user\get_user($row['driver_id']);
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
    elseif (mysql_num_rows($result))
        return mysql_fetch_assoc($result);
    return NULL;
}

/**
  * Returns all the trips near the given route
  * TODO: Sanitize input
  * @param route the route for which to find nearby trips
  * @param Departure time for the ride, null if not specified
  * @return an array of all the trips nearby the route
  */
function get_trips_near_on($route, $departure=NULL) {

    $threshold = 0.25; // The Threshold
    $q_origin_lat = $route['origin']['lat'];
    $q_origin_lon = $route['origin']['lon'];
    $q_dest_lat = $route['destination']['lat'];
    $q_dest_lon = $route['destination']['lon'];
    $trip_table = TRIP_TABLE;
    $place_table = PLACE_TABLE;

    $departure_condition = "";
    if (!$departure) {
        $current_time = time();
        $departure_condition = "tr.departure_time >= $current_time";
    } else {
        $departure_condition = "tr.departure_time >= $departure
                                AND tr.departure_time <= $departure + 86400";
    }

    $search_query = 
        "SELECT tr.* FROM $trip_table as tr, $place_table as pl
         WHERE tr.origin_id = pl.id
             AND pl.lat - 0.25 <= $q_origin_lat
             AND pl.lat + 0.25 >= $q_origin_lat
             AND pl.lon - 0.25 <= $q_origin_lon
             AND pl.lon + 0.25 >= $q_origin_lon
             AND $departure_condition
        
        AND (tr.id) IN

        (SELECT tr.id FROM $trip_table as tr, $place_table as pl
         WHERE tr.destination_id = pl.id
             AND pl.lat - 0.25 <= $q_dest_lat
             AND pl.lat + 0.25 >= $q_dest_lat
             AND pl.lon - 0.25 <= $q_dest_lon
             AND pl.lon + 0.25 >= $q_dest_lon)";

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

/**
* Function to request a ride 
* @param an array contaning info about requested ride
* @return returns true if the request was successfull
* @return Otherwise returns NULL if request wasn't successful
*/

function request_ride($request_data){
    $trip_table = TRIP_REQUEST_TABLE;
    $user_id = functions\sanitize_string($request_data['user_id']);
    $trip_id = functions\sanitize_string($request_data['trip_id']);
    $message = functions\sanitize_string($request_data['message']);

    $query = 
        "INSERT INTO $trip_table (
            message,
            trip_id,
            user_id
        ) VALUES (
            '$message',
            '$trip_id',
            '$user_id')";

    if (!mysql_query($query))
        return false;
    else
        return true;
}