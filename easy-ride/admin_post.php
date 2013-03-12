<?php 
require_once 'functions/functions.php';
require_once 'functions/user.php';

function delete_user($data) {
    if (user\delete_user($data['user_id']))
        return functions\json_respond('OK', 'Deleted!');
    else
        return functions\json_respond('ERROR', 'Couldn\'t delete user.');
}

function update_user($data) {
    if (user\update_user($data))
        return functions\json_respond('OK', 'Updated!');
    else
        return functions\json_respond('ERROR', 'Couldn\'t update user.');
}

if ($_POST) {
    $data = json_decode($_POST['data'], true);

    if ($data['method'] == 'delete_user')
        delete_user($data);
    elseif ($data['method'] == 'update_user')
        update_user($data);
    else
        return functions\json_respond('ERROR', 'Unknown method.');
}

 
?>