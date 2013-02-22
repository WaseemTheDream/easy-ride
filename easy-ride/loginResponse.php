<?php 

include 'head.php';
include    'navbar.php';
include_once 'functions.php';

$error = $email = $pass = "";
if (isset($_POST['email']))
	{
	$email = sanitizeString($_POST['email']);
	$pass = sanitizeString($_POST['password']);
	$counter = 0;
    while ($counter<1000)
          {
          $pass = md5($pass);
          $counter++;
     }

if ($email == "" || $pass == "")
	{
		$error = "Not all fields were entered<br />";
		echo $error;
}
else
	{
	$query = "SELECT password,emailAddress FROM $users_table
	WHERE password='$pass' AND EmailAddress='$email'";
if (mysql_num_rows(queryMysql($query)) == 0)
{
	$error = "<span class='error'>Email/Password
	invalid</span><br /><br />";
	echo $error;
	}
else
{
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $pass;
	$FName_Query = queryMysql("SELECT firstName FROM $users_table WHERE password='$pass' AND EmailAddress='$email'");
	$FName = mysql_result($FName_Query, 0);
	$_SESSION['fName']= $FName;
	header("Location: http://localhost/$url"); 
	
	}
  }
}
include 'footer.php';
?>