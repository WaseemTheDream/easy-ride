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
    departure INT NOT NULL,
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


// Function Adds trip Info the Database

function  add_trip($tripData)
{
    $Add_Spots = $tripData['spots'];
    $length = $tripData['length'];
    $message = $tripData['message'];
    $women_only = $tripData['women_only'];
    $departure = $tripData['departure'];
    $origin_id = $tripData['origin_id'];
    $destination_id = $tripData['destination_id'];

    $TripQuery="INSERT INTO ".TRIP_TABLE." (
                        spots,
                        length,
                        message,
                        women_only,
                        departure,
                        origin_id,
                        destination_id    
                       ) VALUES(
                         '$Add_Spots',
                         '$length',
                         '$message',
                         '$women_only',
                         '$departure',
                         '$origin_id',
                         '$destination_id'
                        )";

    $tripAdd = mysql_query($TripQuery);
    if (!$tripAdd) die("Failed to Add Trip Info because: " . mysql_error());
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