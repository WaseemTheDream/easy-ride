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
    spots_taken TINYINT NOT NULL,
    length VARCHAR(128) NOT NULL,
    message VARCHAR(4096),
    women_only BINARY(1) NOT NULL,
    departure_time INT NOT NULL,
    origin_id INT NOT NULL,
    destination_id INT NOT NULL,
    CONSTRAINT chk_spots CHECK (spots_taken <= spots)
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

$request_status_to_code = array(
    "DECLINED" => -1,
    "PENDING" => 0,
    "APPROVED" => 1);

$request_code_to_status = array(
    -1 => "DECLINED",
    0 => "PENDING",
    1 => "APPROVED");

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
function get_trip($id, $user_id=NULL)
{
    $s_id = functions\sanitize_string($id);
    $query = "SELECT * FROM ".TRIP_TABLE." WHERE id='$s_id'";
    $result = mysql_query($query);
    if (!$result) return NULL;
    elseif (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        return process_trip_row($row, $user_id);
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

function process_trip_row($row, $user_id=NULL)
{
    $row['origin'] = get_place($row['origin_id']);
    $row['destination'] = get_place($row['destination_id']);
    $row['driver'] = user\get_user($row['driver_id']);
    if ($user_id)
        $row['status'] = get_ride_request_status($user_id, $row['id']);
    else
        $row['status'] = NULL;
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
 * Gets all of the upcoming drives for a user.
 * @param driver_id the user id of the driver
 * @return an array of all the upcoming trips
 */
function get_drives_for($driver_id) {
    $current_time = time();
    $s_driver_id = functions\sanitize_string($driver_id);
    $trip_table = TRIP_TABLE;

    $query = "SELECT * FROM $trip_table 
              WHERE driver_id = $s_driver_id
                AND departure_time >= $current_time";

    $result = mysql_query($query);
    $rows = array();
    if ($result) {
        for ($i = 0; $i < mysql_num_rows($result); ++$i) {
            $row = mysql_fetch_assoc($result);
            $rows[] = process_trip_row($row);
        }
    }
    return $rows;
}


function get_rides_for($user_id) {
    $current_time = time();
    $s_user_id = functions\sanitize_string($user_id);
    $trip_table = TRIP_TABLE;
    $trip_request_table = TRIP_REQUEST_TABLE;

    $trips_query = "SELECT trip_id FROM $trip_request_table
                    WHERE user_id = $s_user_id";
    $trips_result = mysql_query($trips_query);
    $trips = array();
    if ($trips_result) {
        for ($i = 0; $i < mysql_num_rows($trips_result); ++$i) {
            $row = mysql_fetch_assoc($trips_result);
            $trips[] = get_trip($row['trip_id'], $user_id);
        }
    }
    return $trips;
}


/**
  * Returns all the trips near the given route
  * @param route the route for which to find nearby trips
  * @param departure (optional) the departure time for the ride
  * @param user_id (optional) the user id of the user requesting the trips
  * @return an array of all the trips nearby the route
  */
function get_trips_near_on($route, $departure=NULL, $user_id=NULL) {

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
            $rows[] = process_trip_row($row, $user_id);
    }
    return $rows;
}

/**
 * Function to request a ride 
 * @param an array contaning info about requested ride
 * @return boolean whether or not the request was successfully made
 */
function request_ride($request_data) {
    $trip_request_table = TRIP_REQUEST_TABLE;
    $user_id = functions\sanitize_string($request_data['user_id']);
    $trip_id = functions\sanitize_string($request_data['trip_id']);
    $message = functions\sanitize_string($request_data['message']);

    $query = 
        "INSERT INTO $trip_request_table (
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

/**
 * Returns all the requests made for a trip.
 * @param trip_id the id of the trip
 * @return an array containing all the requests made for the trip
 */
function get_requests_for_trip($trip_id) {
    global $request_code_to_status;
    $trip_request_table = TRIP_REQUEST_TABLE;
    $s_trip_id = functions\sanitize_string($trip_id);
    $query = "SELECT * FROM $trip_request_table WHERE trip_id = $s_trip_id";
    $rows = array();
    $result = mysql_query($query);
    if ($result) {
        for ($i = 0; $i < mysql_num_rows($result); ++$i) {
            $row = mysql_fetch_assoc($result);
            $row['status'] = $request_code_to_status[$row['status']];
            $row['rider'] = user\get_user($row['user_id']);
            $rows[] = $row;
        }
    }
    return $rows;
}

/**
 * Returns the status of a ride request.
 * @param user_id requestor id
 * @param trip_id the id of the trip
 * @return one of {'DECLINED', 'APPROVED', 'PENDING', 'UNKNOWN'}
 */
function get_ride_request_status($user_id, $trip_id) {
    global $request_code_to_status;
    $trip_request_table = TRIP_REQUEST_TABLE;
    $s_trip_id = functions\sanitize_string($trip_id);
    $s_user_id = functions\sanitize_string($user_id);
    $query = "SELECT * FROM $trip_request_table 
              WHERE user_id = $s_user_id AND trip_id = $s_trip_id";
    $result = mysql_query($query);
    if (!$result) return NULL;
    elseif (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        return $request_code_to_status[$row['status']];
    }
    return NULL;
}

/**
 * Sets the ride request status in the database.
 * @param user_id the id of the user who has requested the ride
 * @param trip_id the id of the trip to update
 * @param status one of {'DECLINED', 'PENDING', 'APPROVED'}
 * @return returns true if the request was successfully updated, false otherwise
 */
function set_ride_request_status($user_id, $trip_id, $status) {
    global $request_status_to_code;
    $trip_request_table = TRIP_REQUEST_TABLE;

    $trip_id = functions\sanitize_string($trip_id);
    $user_id = functions\sanitize_string($user_id);
    $status_code = $request_status_to_code[$status];

    $query = "UPDATE $trip_request_table
                SET status = '$status_code'
              WHERE user_id = '$user_id'";
    if (mysql_query($query)) return true;
    return false;
}

/**
 * Updates the ride request status with constraint checking.
 * @param user_id the id of the user who has requested the ride
 * @param trip_id the id of the trip to update
 * @param status one of {'DECLINED', 'PENDING', 'APPROVED'}
 * @return returns the number of spots remaining in the ride, -1 if unsuccessful
 */
function update_ride_request_status($user_id, $trip_id, $status) {
    $trip = get_trip($trip_id);
    $spots = $trip['spots'];
    $spots_taken = $trip['spots_taken'];
    if ($status == get_ride_request_status($user_id, $trip_id))
        return $spots - update_spots_taken($trip_id);
    else if ($status == 'APPROVED' && $spots - $spots_taken < 1)
        return -1;

    set_ride_request_status($user_id, $trip_id, $status);

    return $spots - update_spots_taken($trip_id);
}

/**
 * Updates the spots taken in the trip.
 * @param trip_id the id of the trip to be updated
 * @return spots_taken the new number of spots taken in trip. -1 if failed.
 */
function update_spots_taken($trip_id) {
    $trip_table = TRIP_TABLE;
    $trip_request_table = TRIP_REQUEST_TABLE;

    $trip_id = functions\sanitize_string($trip_id);

    $spots_query = "SELECT * FROM $trip_request_table
                    WHERE trip_id = '$trip_id' 
                    AND status = 1";

    $spots_taken = mysql_num_rows(mysql_query($spots_query));

    $update_query = "UPDATE $trip_table 
                     SET spots_taken='$spots_taken'
                     WHERE id = '$trip_id'";

    error_log($spots_taken);
    if (mysql_query($update_query))
        return $spots_taken;
    else
        return -1;
}


/**
* Deletes the trip from the trip table and deletes its entries from the place table
* @param trip_id the is of the trip to be deleted
* @return true if the trip was deleted, false otherwise
*/

function delete_trip($trip_id){

    $trip_table = TRIP_TABLE;
    $trip_id = functions\sanitize_string;
    $place_table = PLACE_TABLE;
    $trip = get_trip($trip_id);
    if ($trip){
        $origin = $trip['origin']['id'];
        $destination = $trip['destination']['id'];
        $delete_trip = mysql_query("DELETE FROM $trip_table WHERE id=$trip_id");
        $delete_place_1 = mysql_query("DELETE FROM $place_table WHERE id= $origin ");
        $delete_place_2 = mysql_query("DELETE FROM $place_table WHERE id=$destination");
        if ($delete_trip& $delete_place_1&$delete_place_2)  return true; // Trip successfully deleted
        return false; // Failed to delete the trip
    }
    return false; // The trip is not in the trip table
}
