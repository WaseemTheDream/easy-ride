<?php
session_start();
require_once 'functions/functions.php';
require_once 'functions/database.php';

function search_get($data) {
    $logged_in_user = user\get_logged_in_user();
    if (!$logged_in_user)
        return functions\json_respond('ERROR', 'Login Required!');
    $user_id = $logged_in_user['id'];

    $users = user\get_all_users();

    functions\json_respond('OK', 'Query Performed!', array("users" => $users));
}

function request_post($data) {}

if ($_GET) {
    search_get(json_decode($_GET['data'], true));
} elseif ($_POST) {
    request_post(json_decode($_POST['data'], true));
}