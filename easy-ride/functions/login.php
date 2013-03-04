
<?php
/* the Login process */

include_once 'functions.php';

$error = "";

// Check if there wasn't any empty post request 

if (!empty($_POST))
{

   
            /* Make sure something was entered in the password and e-mail fields before doing anything else */

            if (isset($_POST['email'])& isset($_POST['password']))

                {
                  $email = sanitizeString($_POST['email']);
                  $pass = sanitizeString($_POST['password']);

                  /* The salt String to increase password security */
                  $saltString = "rideLikeABallerEasyRide";

                  /* Encrypt the password with sha256 hashing algorithm and the provided salt */
                  $pass = hash('sha256',$pass.$saltString);

                  /* The Database Query */

                  $query = "SELECT password,emailAddress FROM $users_table
                            WHERE password='$pass' AND emailAddress='$email'";


                  /* Check to see if the email and password are those stored in our database */


                  if (mysql_num_rows(queryMysql($query)) == 0)
                  {

                        // If nothing was returned, then either the password or the e-mail is wrong
                         
                        $error = "<span class='error'>Email/Password invalid</span><br /><br />";
                        echo $error;
                  }

                  // Success, the password and the e-mail do exist in our database 
                  else

                  {
                        // Set the session e-mail and password

                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $pass;

                        // Query for the User's first name to display on the page

                        $FName_Query = queryMysql("SELECT firstName 
                                                   FROM $users_table 
                                                   WHERE password='$pass' AND EmailAddress='$email'"
                                                   );
                        // Get the actual First name

                        $FName = mysql_result($FName_Query, 0);

                        // Set the first name for the current user

                        $_SESSION['fName']= $FName;
                    
                    }  // End of the else statement for successful login

              } // End of the if statement that check whether or not the e-mail and password were post in the request

         
} // End of the main if statement that checks for empty post requests
    

?>