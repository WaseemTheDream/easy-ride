<?php
require_once 'templates/functions.php';

if ($_POST) {
    $data = json_decode($_POST['data'], true);
    postStatusRespond('OK', 'Trip Saved!');
}

?>