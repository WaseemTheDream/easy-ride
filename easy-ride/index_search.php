<?php
require_once 'functions/functions.php';
require_once 'functions/database.php';

function search_get() {
    $data = json_decode($_GET['data'], true);

    // Get all trips for now
    // $trips = database\get_all_trips();
    $trips = database\get_trips_near_on($data['route'], $data['departure']);
    if ($trips == NULL) $trips = array(); //database\get_all_trips();
    
    $trips_found = array("trips" => $trips);
    functions\json_respond('OK', 'Searched!', $trips_found);
}

if ($_GET) {
    search_get();
}