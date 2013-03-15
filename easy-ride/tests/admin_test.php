<?php
require_once '../admin_post.php';
require_once '../functions/user.php';
require_once '../functions/database.php';

function user_1() {
    return array(
        'first_name' => 'User',
        'last_name' => 'One',
        'email_address' => 'User@One.com',
        'drivers_license_id' => '1234',
        'gender' => 'm',
        'password' => 'UserOne1234');
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
        $origin = $trip['origin'];
        $destination = $trip['destination'];
        $delete_trip = mysql_query("DELETE FROM $trip_table WHERE id=$trip_id");
        $delete_place = mysql_query("DELETE FROM $place_table WHERE id= $origin or id=$destination");
        if ($delete_trip and $delete_place) {
            var_dump("Deleted Trip!");
        return true; // Trip successfully deleted
    }
        return false; // Failed to delete the trip
    }
    var_dump("Not Deleted!");
    return false; // The trip is not in the trip table
}

// admin_test_main();
//trip_spots();


delete_trip(2);

?>