<!DOCTYPE html>
<html lang='en'>
<?php 
    include 'head.php'; 
    include 'navbar.php'; 
    include_once 'functions.php';
?>

<body>

<?php 
    

    //Show some error if smth went wrong:
    $errors = array(); 

    // Check if there was no empty post request 

    if (!empty($_POST))

    { 

        // We need to make sure all the required fields were entered before storing the user's details

        if (  
              isset($_POST['register-email'])
              & isset($_POST['register-password'])
              & isset($_POST['register-first-name'])
              & isset($_POST['register-last-name'])
              & isset($_POST['register-gender'])
            )
        {

                $error = $email = $password = "";

                $email = sanitizeString($_POST['register-email']);
                $pass = sanitizeString($_POST['register-password']);
                $first_name = sanitizeString($_POST['register-first-name']);
                $last_name = sanitizeString($_POST['register-last-name']);
                $gender = sanitizeString($_POST['register-gender']);
                $driver_License = sanitizeString($_POST['register-driver-license-id']);
                
                /* The salt String to increase password security */
                $saltString = "rideLikeABallerEasyRide";

                /* Encrypt the password with sha256 hashing algorithm and the provided salt */
                $pass = hash('sha256',$pass.$saltString);

                // Insert Details into the users table
                $query="INSERT INTO $users_table (
                                                    firstName,
                                                    lastName,
                                                    password,
                                                    emailAddress,
                                                    gender,
                                                    driversLicenseID
                                                  ) 

                                    VALUES(
                                                  '$first_name',
                                                  '$last_name',
                                                  '$pass',
                                                  '$email',
                                                  '$gender',
                                                  '$driver_License'
                                                  )";

                // If the query wasn't successful, redirect to the register failure page

                if (!queryMysql($query))
                {

                 header("Location: register_Failed.php");
                }
                
                // Otherwise, the query was successful, let the user know it was a successful operation 

                else

                {

                    // Automatically login the user. For that, set the session's e-mail and password
                    // And also set the first name

                  $_SESSION['email'] = $email;
                  $_SESSION['password'] = $pass;
                  $_SESSION['fName']= $first_name;

                  header("Location: register_Success.php"); 
                 
                } // End of the else statement that notifies the user of a successful registration


        }  // End of the if statement that checks if some required variables are set

  // Close the connection to the database 

  mysql_close($connection);
            
  } // End of the main if close that checks for empty post requests 

                 
  include 'footer.php';

    ?>

</body>
</html>