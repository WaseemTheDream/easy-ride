<?php
namespace user;
require_once 'functions.php';
use functions;

define("USER_TABLE", 'user');

// Table Definition
$user_table_definition = USER_TABLE."
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(64) NOT NULL,
    last_name VARCHAR(64) NOT NULL,
    email_address VARCHAR(128) NOT NULL UNIQUE,
    drivers_license_id VARCHAR(64),
    gender BINARY(1) NOT NULL,
    password VARCHAR(64) NOT NULL
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
    $s_email = functions\sanitize_string($email);
    $query = "SELECT * FROM ".USER_TABLE." WHERE email_address='$s_email'";
    $result = mysql_query($query);
    if (!$result) die("Database access failed: " . mysql_error());
    elseif (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        $input_token = encrypt_password($password);
        if ($row['password'] == $input_token) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email_address'] = $row['email_address'];
            $_SESSION['first_name'] = $row['first_name'];
            return $row;
        } else {
            return NULL;
        }
    }
}

/**
 * Logs out the user by destroying session.
 * @return status LOGGED_OUT if user logged out,
 *         NOT_LOGGED_IN if user is not logged in.
 */
function logout_user() {
    if (!isset($_SESSION['user_id']))
        return 'NOT_LOGGED_IN';
    $_SESSION = array();
    if (session_id() != "" or isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');
    session_destroy();
    return 'LOGGED_OUT';
}

/**
 * Whether the user is logged in.
 * @return boolean whether the user is logged in.
 */
function user_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Adds the user to the database.
 * @param data associative array containing all of the user information.
 */
function add_user($data) {
    $first_name = functions\sanitize_string($data['first_name']);
    $last_name = functions\sanitize_string($data['last_name']);
    $email_address = functions\sanitize_string($data['email_address']);
    $drivers_license_id = functions\sanitize_string($data['drivers_license_id']);
    $gender = functions\sanitize_string($data['gender']);
    $password = encrypt_password($data['password']);
    $query = "INSERT INTO ".USER_TABLE." (
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
 * Gets the user specified by id.
 * @param id the row id of the user.
 * @return row the user row in the database without password, NULL otherwise.
 */
function get_user($id) {
    $s_id = functions\sanitize_string($id);
    $query = "SELECT first_name, 
                     last_name, 
                     email_address,
                     drivers_license_id,
                     gender 
              FROM ".USER_TABLE." WHERE id=$s_id";
    $result = mysql_query($query);
    if (!$result) return NULL;
    elseif (mysql_num_rows($result))
        return mysql_fetch_assoc($result);
    return NULL;
}

/**
 * Checks whether the user exists.
 * @param email the email address of the user.
 * @return boolean whether the user exists.
 */
function user_exists($email) {
    $s_email = functions\sanitize_string($email);
    $query = "SELECT * FROM ".USER_TABLE." WHERE email_address='$s_email'";
    if (mysql_num_rows(mysql_query($query)))
        return true;
    else
        return false;
}


?>