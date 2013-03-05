<?php
require_once 'functions/functions.php';
require_once 'functions/database.php';

function share_post() {
    $data = json_decode($_POST['data'], true);

    $log = "";

    $origin_id = add_place($data['route']['from']);
    $destination_id = add_place($data['route']['to']);

    if (!($origin_id or $destination_id)) {
        json_respond('ERROR', "Couldn't store places!");
        return;
    }

    $log .= "Origin ID: $origin_id\n Destination ID: $destination_id\n";

    $trip_data = array(
        'spots' => $data['spots'],
        'length' => $data['route']['length'],
        'message' => $data['message'],
        'women_only' => $data['women_only'],
        'departure_time' => $data['departure'],
        'origin_id' => $origin_id,
        'destination_id' => $destination_id);

    $trip_id = add_trip($trip_data);

    if (!$trip_id) {
        json_respond('ERROR', "Couldn't store trip!");
    }

    $log .= "Trip ID: $trip_id\n";

    json_respond('OK', 'Trip saved!', $log);
}

if ($_POST) {
    share_post();
}

?>