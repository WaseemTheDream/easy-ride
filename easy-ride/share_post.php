<?php
require_once 'functions/functions.php';
require_once 'functions/database.php';

function share_post() {
    $data = json_decode($_POST['data'], true);

    // Get driver info
    $driver_id = $_SESSION['user_id'];
    if (!$driver_id)
        return functions\json_respond('ERROR', "User not logged in!");

    // Store places
    $origin_id = database\add_place($data['route']['origin']);
    $destination_id = database\add_place($data['route']['destination']);
    if (!($origin_id or $destination_id))
        return functions\json_respond('ERROR', "Couldn't store places!");
    
    // Store trip
    $trip_data = array(
        'driver_id' => $driver_id,
        'spots' => $data['spots'],
        'length' => $data['route']['trip_length'],
        'message' => $data['message'],
        'women_only' => $data['women_only'],
        'departure_time' => $data['departure'],
        'origin_id' => $origin_id,
        'destination_id' => $destination_id);
    $trip_id = database\add_trip($trip_data);
    if (!$trip_id)
        return json_respond('ERROR', "Couldn't store trip!");

    functions\json_respond('OK', 'Trip saved!');
}

if ($_POST) {
    session_start();
    share_post();
}

?>