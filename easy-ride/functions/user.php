<?php

require_once 'functions.php';

$user_table = 'user';

// Create  table
$create_user_table = "CREATE TABLE $user_table
(
    user_id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (user_id),
    first_name varchar(64) NOT NULL,
    last_name varchar(64) NOT NULL,
    email_address varchar(128) NOT NULL UNIQUE,
    drivers_license_id varchar(64),
    gender binary(1) NOT NULL,
    password varchar(64) NOT NULL
)";

/**
 * Encrypts the given password.
 * @param password Input password.
 * @return string the salted and hashed string of the given password.
 */
function encrypt_password($password) {
    $salt1 = "qm&h*";
    $salt2 = "ez!@";
    return hash('sha256', $salt1.$password.$salt2);
}

/**
 * Authenticates the user.
 * @param email the email address of the user.
 * @param password the password entered by the user.
 * @return row the associative row of the user if authenticated, NULL otherwise.
 */
function authenticate_user($email, $password) {
    $query = "SELECT * FROM user WHERE email_address='$email'";
    $result = mysql_query($query);
    if (!$result) die("Database access failed: " . mysql_error());
    elseif (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        $input_token = encrypt_password($password);
        if ($row['password'] == $input_token) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email_address'] = $row['email_address'];
            $_SESSION['first_name'] = $row['first_name'];
            return $row;
        } else {
            return NULL;
        }
    }
}

/**
 * Adds the user to the database.
 * @param user_data associative array containing all of the user information.
 */
function add_user($user_data) {
    $first_name = $user_data['first_name'];
    $last_name = $user_data['last_name'];
    $email_address = $user_data['email_address'];
    $drivers_license_id = $user_data['drivers_license_id'];
    $gender = $user_data['gender'];
    $password = encrypt_password($user_data['password']);
    $query = "INSERT INTO user (
            first_name,
            last_name,
            email_address,
            drivers_license_id,
            gender,
            password
        ) VALUES (
            '$first_name',
            '$last_name',
            '$email_address',
            '$drivers_license_id',
            '$gender',
            '$password')";
    $result = mysql_query($query);
    if (!$result) die("Failed to create user: " . mysql_error());
}

/**
 * Checks whether the user exists.
 * @param email the email address of the user.
 * @return boolean whether the user exists.
 */
function user_exists($email) {
    $query = "SELECT * FROM $user_table WHERE email_address='$email'";
    if (mysql_num_rows(mysql_query($query)))
        return true;
    else
        return false;
}


?>