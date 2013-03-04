<?php

require_once 'functions/functions.php';

if ($_POST) {
    $data = json_decode($_POST['data'], true);
    postStatusRespond('OK', 'Trip Saved!');
}

?>