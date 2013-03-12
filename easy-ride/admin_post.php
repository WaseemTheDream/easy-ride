<?php 
require_once 'functions/functions.php';
require_once 'functions/database.php';
require_once 'functions/user.php';

$data = array('method' => 'update_user' );
$user_id = 4;

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
    $failed_updates = array();
    $updates_array= array('first_name'=> 'Mig',
                           'last_name'=> 'Cyuzuzo',
                           'drivers_license_id'=> 'DTJh0045',
                           'gender'=> 'f'
                            );
    foreach ($updates_array as $column_name => $new_entry) {
        $update= user\update_entry($column_name,$new_entry,$user_id);
        if(!$update){
            $failed_updates[$column_name] = $new_entry;
        }
    }
    if($failed_updates){
         functions\json_respond('ERROR', 'Try Again!');
    }
    else{
        functions\json_respond('OK', 'User Updated!');
    }
}

 
?>