<?php
require_once 'admin_post.php';
require_once 'functions/user.php';

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

admin_test_main();

?>