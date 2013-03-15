<?php
session_start();
require_once 'functions/functions.php';
require_once 'functions/database.php';

function search_get($data) {
    $logged_in_user = user\get_logged_in_user();
    if (!$logged_in_user)
        return functions\json_respond('ERROR', 'Login Required!');
    $user_id = $logged_in_user['id'];

    $trips = database\get_trips_for($user_id);
    if ($trips == NULL)
        return functions\json_respond('ERROR', 'Query Error!');

    functions\json_respond('OK', 'Query Performed!', array("trips" => $trips));
}

function request_post($data) {}

if ($_GET) {
    search_get(json_decode($_GET['data'], true));
} elseif ($_POST) {
    request_post(json_decode($_POST['data'], true));
}