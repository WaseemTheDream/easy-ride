<?php
require_once 'user.php';
session_start();
$status = "";
$msg = "";
function logout_main() {
    global $status, $msg;
    if (user\logout_user() == 'LOGGED_OUT') {
        $status = 'Logged Out!';
        $msg = 'You have successfully logged out. Come back soon!';
        return;
    } else {
        $status = 'Error!';
        $msg = "You weren't logged in.";
    }
}

logout_main();
include_once '../templates/head.php';
require_once 'functions.php';
?>
<div class="well ds-component ds-hover container-narrow" data-componentid="well1">
<div class="ds-component ds-hover" data-componentid="content2">
    <?php functions\html_respond($status, $msg); ?>
</div>
</div>
<?php include '../templates/footer.php'; ?>