// Logout script

<?php 

session_start();
session_destroy();
header("Location: http://localhost/$url"); //locahost because we're using our local servers.
?>