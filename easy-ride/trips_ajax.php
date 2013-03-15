<?php
session_start();
require_once 'functions/functions.php';
require_once 'functions/database.php';

function get_upcoming_drives($user_id, $data) {
    $drives = database\get_drives_for($user_id);
    functions\json_respond(
        'OK', 'Query Performed!', array("drives" => $drives));
}

function get_requests_for_trip($user_id, $data) {
    $trip = database\get_trip($data['trip_id']);

    if ($trip['driver_id'] != $user_id)
        return functions\json_respond('ERROR', 'Unauthorized access!');

    $requests = database\get_requests_for_trip($data['trip_id']);
    functions\json_respond(
        'OK', 'Query performed!', array("requests" => $requests));
}

function get_spots_remaining_for_trip($user_id, $data) {
    $trip = database\get_trip($data['trip_id']);
    $out = array(
        "spots_remaining" => $trip['spots'] - $trip['spots_taken'],
        "spots_taken" => intval($trip['spots_taken']));
    functions\json_respond('OK', 'Query performed', $out);
}

function get_rides($user_id, $data) {
    $rides = database\get_rides_for($user_id);
    functions\json_respond('OK', 'Query performed', array("rides" => $rides));
}

function update_ride_request_status($driver_id, $data) {
    $trip = database\get_trip($data['trip_id']);

    if ($trip['driver_id'] != $driver_id)
        return functions\json_respond('ERROR', 'Unauthorized access!');

    $spots_remaining = database\update_ride_request_status(
        $data['user_id'], $data['trip_id'],$data['status']);

    if ($spots_remaining < 0)
        return functions\json_respond('ERROR', 'Insufficient spots!');

    functions\json_respond(
        'OK', 'Query performed!', array("spots_remaining" => $spots_remaining));
}

function request_post($data) {}

if ($_GET) {
    // Get logged in user
    $logged_in_user = user\get_logged_in_user();
    if (!$logged_in_user)
        return functions\json_respond('ERROR', 'Login required!');
    $user_id = $logged_in_user['id'];

    // Decode json data
    $data = json_decode($_GET['data'], true);
    $method = $_GET['method'];

    // Call appropriate method
    if ($method == 'get_upcoming_drives')
        return get_upcoming_drives($user_id, $data);
    else if ($method == 'get_requests_for_trip')
        return get_requests_for_trip($user_id, $data);
    else if ($method == 'get_spots_remaining_for_trip')
        return get_spots_remaining_for_trip($user_id, $data);
    else if ($method == 'get_rides')
        return get_rides($user_id, $data);
    else
        return functions\json_respond('ERROR', 'Unknown method!');
} elseif ($_POST) {
    // Get logged in user
    $logged_in_user = user\get_logged_in_user();
    if (!$logged_in_user)
        return functions\json_respond('ERROR', 'Login required!');
    $user_id = $logged_in_user['id'];

    // Decode json data
    $data = json_decode($_POST['data'], true);
    $method = $_POST['method'];

    if ($method == 'update_ride_request_status')
        return update_ride_request_status($user_id, $data);
    else
        return functions\json_respond('ERROR', 'Unknown method!');
}