<?php
session_start();
require_once '../functions/user.php';
$status = "";
$msg = "";

function login_main() {
    global $status, $msg;

    if (empty($_POST)) {
        $status = 'Error!';
        $msg = 'Login information not specified.';
        return;
    }

    if (empty($_POST['login-email']) or empty($_POST['login-password'])) {
        $status = 'Error!';
        $msg = 'Login information not specified.';
        return;
    }

    $email_address = $_POST['login-email'];
    $password = $_POST['login-password'];

    if (user\authenticate_user($email_address, $password)) {
        $status = 'Logged In!';
        $msg = 'You have successfully logged in to Easy Ride!';
    } else {
        $status = 'Invalid Credentials!';
        $msg = 'You have specified an invalid combination of email address and password.';
    }
}

login_main();
include_once '../templates/head.php';
?>
<div class="well ds-component ds-hover container-narrow" data-componentid="well1">
<div class="ds-component ds-hover" data-componentid="content2">
    <?php functions\html_respond($status, $msg); ?>
</div>
</div>
<?php include '../templates/footer.php'; ?>