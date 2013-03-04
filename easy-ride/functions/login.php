<html>
<?php
    include '../templates/head.php';
    include '../templates/navbar.php';
    require_once '../functions/user.php';
?>
<body>
<div class="well ds-component ds-hover container-narrow" data-componentid="well1">
<div class="ds-component ds-hover" data-componentid="content2">
<?php

    function main() {
        if (empty($_POST)) {
            echo "Login information not specified.";
            return;
        }

        if (empty($_POST['login-email']) or empty($_POST['login-password'])) {
            echo "Missing login information.";
            return;
        }

        $email_address = sanitize_string($_POST['login-email']);
        $password = sanitize_string($_POST['login-password']);

        if (authenticate_user($email_address, $password)) {
            html_respond('Logged In!', 'You have successfully logged in to Easy Ride!');
        } else {
            html_respond('Invalid Credentials!', 'You have specified an invalid combination of email address and password.');
        }
    }
    
    main();
?>
</div>
</div>
<?php include '../templates/footer.php'; ?>
</body>
</html>