<?php
session_start();
require_once 'functions/functions.php';
require_once 'functions/database.php';

function get_upcoming_drives($user_id, $data) {
    
    
    

    $drives = database\get_drives_for($user_id);

    functions\json_respond('OK', 'Query Performed!', array("drives" => $drives));
}

function get_requests_for_ride($user_id, $data) {
}

function request_post($data) {}

if ($_GET) {
    // Get logged in user
    $logged_in_user = user\get_logged_in_user();
    if (!$logged_in_user)
        return functions\json_respond('ERROR', 'Login Required!');
    $user_id = $logged_in_user['id'];

    // Decode json data
    $data = json_decode($_GET['data'], true);
    $method = $_GET['method'];

    // Call appropriate method
    if ($method == 'get_upcoming_drives')
        return get_upcoming_drives($user_id, $data);
    else if ($method == 'get_requests_for_ride')
        return get_requests_for_ride($user_id, $data);
    else
        return json_respond('ERROR', 'Unknown Method!');
} elseif ($_POST) {
    request_post(json_decode($_POST['data'], true));
}