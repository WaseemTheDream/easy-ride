<?php
require_once 'functions/functions.php';

if ($_POST) {
    $data = json_decode($_POST['data'], true);
    $log = "From: ".$data['route']['from']['address'];
    $log .= " To: ".$data['route']['to']['address'];
    json_respond('OK', 'Trip Saved!', $log);
    // You can also do
    // json_respond('OK', 'Trip Saved!');
}

?>