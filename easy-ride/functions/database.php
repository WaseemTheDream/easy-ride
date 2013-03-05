<?php

require_once 'functions.php';

define("TRIP_TABLE", 'trip');
define("PLACE_TABLE", 'place');

// Trip Table Definition
$trip_table_definition = TRIP_TABLE."
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
    $spots = $data['spots'];
    $length = $data['length'];
    $message = $data['message'];
    $women_only = $data['women_only'];
    $departure_time = $data['departure_time'];
    $origin_id = $data['origin_id'];
    $destination_id = $data['destination_id'];

    $query = "INSERT INTO ".TRIP_TABLE." (
            spots,
            length,
            message,
            women_only,
            departure_time,
            origin_id,
            destination_id
        ) VALUES (
            '$spots',
            '$length',
            '$message',
            '$women_only',
            '$departure_time',
            '$origin_id',
            '$destination_id')";

    $result = mysql_query($query);
    if (!$result)
        echo "Failed to Add Trip Info because: " . mysql_error();
    else
        return mysql_insert_id();
}

// Add Adress to the Database
function add_place($AddressData)
{

    $address =  $AddressData['address'];
    $lat = $AddressData['lat'];
    $lon = $AddressData['lon'];

    $AddressQuery ="INSERT INTO ".PLACE_TABLE." (
                                address,
                                lat,
                                lon 
                        ) VALUES (
                        '$address',
                        '$lat',
                        '$lon'
                )";
    $AddressAdd = mysql_query($AddressQuery);
    if (!$AddressAdd) die("Failed to Add Address because: " . mysql_error());
}