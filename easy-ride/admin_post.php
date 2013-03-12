<?php 
require_once 'functions/functions.php';
require_once 'functions/database.php';
require_once 'functions/user.php';

$data = array('method' => 'update_user' );
$user_id =4;
$user_table = 'user';
if($data['method'] == 'delete_user'){
    $delete = user\delete_user($user_id);
    var_dump($delete);
    if($delete){
                functions\json_respond('OK', 'User Deleted!');
        }
    else{
                functions\json_respond('ERROR', 'Try Again!');
        }

}
elseif ($data['method'] == 'update_user') {
    $failed_updates = false;
    $query = "UPDATE $user_table SET first_name='Mig'
              WHERE  $user_table.id=$user_id";
    if (!mysql_query($query)) {
        var_dump(mysql_error());
        $failed_updates = true;
    }
    if($failed_updates){
         functions\json_respond('ERROR', 'Try Again!');
    }
    else{
        functions\json_respond('OK', 'User Updated!');
    }
}

 
?>