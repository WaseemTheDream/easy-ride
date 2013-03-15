<?php
require_once '../admin_post.php';
require_once '../functions/user.php';
require_once '../functions/database.php';

function user_1() {
    return array(
        'first_name' => 'User',
        'last_name' => 'One',
        'email_address' => 'User@One123.com',
        'drivers_license_id' => '1234',
        'gender' => 'm',
        'password' => 'user');
}

function admin_test_main() {

    // Create user
    echo '<br><h3>Creating User.</h3><br>';
    $user_1 = user_1();
    user\add_user($user_1);
    echo '<br><h3>User created!</h3><br>';

    // Verify
    $user_1_db = user\authenticate_user(
        $user_1['email_address'],
        $user_1['password']);
    $user_1_id = $user_1_db['id'];
    echo "<br><h3>User ID: $user_1_id</h3><br>";

    // Update user
    echo "<br><h3>Updating User.</h3><br>";
    $user_1_db['email_address'] = 'User@Two.com';
    $user_1_db['last_name'] = 'Two';
    update_user($user_1_db);
    echo "<br><h3>User Updated!</h3><br>";

    // Verify
    $user_2_db = user\authenticate_user(
        'User@Two.com',
        $user_1['password']);
    $new_last_name = $user_2_db['last_name'];
    echo "<br><h3>New User Last Name: $user_2_db";

    // Delete user
    echo "<br><h3>Deleting User.</h3><br>";
    delete_user(array("user_id" => $user_1_id));
    echo "<br><h3>User deleted!</h3><br>";
}

function trip_spots() {
    echo '<br>';
    $trip = database\get_trip(1);
    echo json_encode($trip);
    echo '<br>';
    $ride_status = database\get_ride_request_status(1, 1);
    echo json_encode($ride_status);
    echo json_encode(database\update_ride_request_status(1, 1, 'APPROVED'));


}

function delete_trip($trip_id){

    $trip_table = 'trip';
    $place_table = 'place';
    $trip = database\get_trip($trip_id);
    if ($trip){
        $origin = $trip['origin']['id'];
        $destination = $trip['destination']['id'];
        var_dump($origin,$destination);
        $delete_trip = mysql_query("DELETE FROM $trip_table WHERE id=$trip_id");
        $delete_place_1 = mysql_query("DELETE FROM $place_table WHERE id= $origin ");
        $delete_place_2 = mysql_query("DELETE FROM $place_table WHERE id=$destination");
        if ($delete_trip& $delete_place_1&$delete_place_2) {
            var_dump("Deleted Trip!");
        return true; // Trip successfully deleted
    }
        return false; // Failed to delete the trip
    }
    var_dump("Not Deleted!");
    return false; // The trip is not in the trip table
}


/**
 * Deletes the following user from the database.
 * @param $user_id the database id of the user.
 * @return boolean whether the user was successfully deleted.
 */
function delete_user1($user_id){
    $user_table = USER_TABLE;
    $trip_table = 'trip';
    $s_user_id= $user_id;
    //$users_table_delete = mysql_query("DELETE FROM $user_table WHERE id = $s_user_id");
    $users_trips = mysql_query("SELECT * FROM $trip_table WHERE driver_id=$s_user_id");
    $num_rows = mysql_num_rows($users_trips);
    //if ($users_table_delete){
        if ($users_trips ){
            for ($i = 0; $i < $num_rows ; ++$i) {
                $row = mysql_fetch_assoc($users_trips);
                var_dump($row);
                var_dump($row['id']);
                $trip_delete = database\delete_trip($row['id']);
               if($trip_delete )var_dump("deleted Trip!<br>");
            }
            return "No Trips for the User...";
        }
    return false; // Failed to delete User
}
// admin_test_main();
//trip_spots();


//delete_trip(4);
//$user_1 = user_1();
//user\add_user($user_1);
//echo '<br><h3>User created!</h3><br>';
//delete_user1(6);

?>