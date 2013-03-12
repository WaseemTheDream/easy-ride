<?php
session_start();
require_once 'functions/functions.php';
require_once 'functions/database.php';

function search_get($data) {
    $trips = database\get_trips_near_on($data['route'], $data['departure']);
    if ($trips == NULL) $trips = array();
    $trips_found = array("trips" => $trips);
    functions\json_respond('OK', 'Searched!', $trips_found);
}

function request_post($data) {
    // TODO: Add request to the database
    $trip_id = $data['trip_id'];
    $logged_in_user = user\get_logged_in_user();
    if (!$logged_in_user)
        return functions\json_respond('ERROR', 'Login Required!');

    $user_id = $logged_in_user['id'];
    if (database\request_ride($trip_id, $user_id))
        return functions\json_respond('OK', 'Request Sent!');
    else
        return functions\json_respond('ERROR', 'Unable to request ride.');
}

if ($_GET) {
    search_get(json_decode($_GET['data'], true));
} elseif ($_POST) {
    request_post(json_decode($_POST['data'], true));
}