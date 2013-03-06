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
function  add_trip($data)
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