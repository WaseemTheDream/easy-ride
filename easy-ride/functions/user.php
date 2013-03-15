<?php
namespace user;
require_once 'functions.php';
require_once 'database.php';
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
* Get User user's data if the user is logged in
* @return if the user is logged in, returns current user's data
* @return otherwise if the user is not logged in, returns NULL
*/

function get_logged_in_user(){
    $data_array=array();
    if(isset($_SESSION['user_id'])){
        $data_array['id'] = $_SESSION['user_id'];
        return $data_array;
    }else{
        return NULL;
    }
}
/**
 * Adds the user to the database.
 * @param data associative array containing all of the user information.
* @return boolean whether the operation was successful.
 */
function add_user($data) {
    $user_table = USER_TABLE;
    $first_name = functions\sanitize_string($data['first_name']);
    $last_name = functions\sanitize_string($data['last_name']);
    $email_address = functions\sanitize_string($data['email_address']);
    $drivers_license_id = functions\sanitize_string($data['drivers_license_id']);
    $gender = functions\sanitize_string($data['gender']);
    $password = encrypt_password($data['password']);
    $query = "INSERT INTO $user_table (
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
    if (mysql_query($query)) return true;
    error_log("Failed to create user: " . mysql_error());
    return false;
}

/**
 * Updates the following user from the database.
 * @param $data an associative array containing the new user data.
 * @return boolean whether the user update was successful.
 */
function update_user($data) {
    $user_table = USER_TABLE;
    $id = functions\sanitize_string($data['id']);
    $first_name = functions\sanitize_string($data['first_name']);
    $last_name = functions\sanitize_string($data['last_name']);
    $email_address = functions\sanitize_string($data['email_address']);
    $drivers_license_id = functions\sanitize_string($data['drivers_license_id']);
    $gender = functions\sanitize_string($data['gender']);
    $password = encrypt_password($data['password']);
    $query = 
        "UPDATE $user_table SET 
            first_name = '$first_name',
            last_name = '$last_name',
            email_address = '$email_address',
            drivers_license_id = '$drivers_license_id',
            gender = '$gender',
            password = '$password'
         WHERE id = $id";
    if (mysql_query($query)) return true;
    error_log("Failed to update user: " . mysql_error());
    return false;
}

/**
 * Deletes the following user from the database.
 * @param $user_id the database id of the user.
 * @return boolean whether the user was successfully deleted.
 */
function delete_user($user_id){
    $user_table = USER_TABLE;
    $trip_table = 'trip';
    $s_user_id= functions\sanitize_string($user_id);
    $users_table_delete = mysql_query("DELETE FROM $user_table WHERE id = $s_user_id");
    $users_trips = mysql_query("SELECT * FROM $trip_table WHERE driver_id=$s_user_id");
    $num_rows = mysql_num_rows($users_trips);
    if ($users_table_delete){
        if ($users_trips ){
            for ($i = 0; $i < $num_rows ; ++$i) {
                $row = mysql_fetch_assoc($users_trips);
                $trip_delete = database\delete_trip($row['id']);
               
            }
            return true;
        }
        return true; // No trips associated with the user
    }
    return false; // Failed to delete User
}

/**
 * Gets the user specified by id.
 * @param id the row id of the user.
 * @return row the user row in the database without password, NULL otherwise.
 */
function get_user($id) {
    $s_id = functions\sanitize_string($id);
    $user_table = USER_TABLE;
    $query = "SELECT id,
                     first_name, 
                     last_name, 
                     email_address,
                     drivers_license_id,
                     gender 
              FROM  $user_table WHERE id=$s_id";
    $result = mysql_query($query);
    if (!$result) return NULL;
    elseif (mysql_num_rows($result))
        return mysql_fetch_assoc($result);
    return NULL;
}

/**
* Gets all the users in the Database as well as their info
* @return returns an array of all of the users in the database
*/

function get_all_users(){
    $users_table = USER_TABLE;
    $users_query = "SELECT *
                    FROM $users_table";
    $query_result = mysql_query( $users_query);
    $rows = array();
    $num_rows = mysql_num_rows($query_result);
    if ($query_result) {
        for ($i = 0; $i < $num_rows ; ++$i) {
            $row = mysql_fetch_assoc($query_result);
            $rows[] = process_trip_row($row);
        }
    }
    return $rows;
}


/**
* Process a User's row
* @param user_id the id of the user
* @return returns an array of all of the user's details
*/
function process_user_row($user_id)
{   
    $row = get_user($user_id);
    if ($row){

    $row['id'] = $user_id;
    $row['first_name'] = $row['first_name'];
    $row['last_name'] = $row['last_name'];
    $row['email_address'] = $row['email_address'];
    $row['drivers_license_id'] = $row['drivers_license_id'];
    $row['gender'] = $row['gender'];
     }
    return $row;
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