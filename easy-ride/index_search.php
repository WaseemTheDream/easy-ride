<?php
require_once 'functions/functions.php';
require_once 'functions/database.php';

function search_get() {
    $data = json_decode($_GET['data'], true);

    functions\json_respond('OK', 'Searched!', 'Hello World!');
}

if ($_GET) {
    search_get();
}