<?php
session_start();
require_once 'functions/user.php';
require_once 'functions/functions.php';
$status = "";
$msg = "";

if (empty($_POST)) {
    $status = 'Error!';
    $msg = 'Registration information not specified.';
} else {
    $user_data = array();
    $required = array(
        'register-email' => 'email_address',
        'register-password' => 'password',
        'register-first-name' => 'first_name',
        'register-last-name' => 'last_name',
        'register-gender' => 'gender');

    // Make sure all required fields are defined
    $missing_fields = array();
    foreach ($required as $post_key => $db_key) {
        if (empty($_POST[$post_key])) {
            $missing_fields[] = $post_key;
        } else {
            $user_data[$db_key] = $_POST[$post_key];
        }
    }

    // Copy over non-required fields
    $user_data['drivers_license_id'] = $_POST['register-drivers-license-id'];

    if ($missing_fields) {
        $status = 'Error!';
        $msg = 'Missing fields: ' . implode(', ', $missing_fields);
    } else {
        user\add_user($user_data);
        user\authenticate_user($user_data['email_address'], $user_data['password']);
        $status = 'Success!';
        $msg = 'You have successfully registered for Easy Ride!';
    }
}
include 'templates/head.php';
?>
<div class="well ds-component ds-hover container-narrow" data-componentid="well1">
<div class="ds-component ds-hover" data-componentid="content2">
    <?php functions\html_respond($status, $msg); ?>
</div>
</div>
<?php include 'templates/footer.php'; ?>