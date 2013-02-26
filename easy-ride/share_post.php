<?php
include 'head.php';
include 'navbar.php';
include_once 'functions.php'; 


error_reporting(E_ALL);
ini_set('display_errors', 'on');

if ($_POST) {
  
$postedData = sanitizeString($_POST['data']);
$postedData = str_replace("&quot;"," ''",$postedData);

$data = json_encode($postedData);

$phpData = json_decode($data,true);

$dataArray = explode("message", $phpData,2);

$messageString = $dataArray[1];
$restData = $dataArray[0];

$delimiterArray = array("departure","route","from","to",
                          "address","lat","lon",
                          "spots","email",
                          "trip_length","women_only",
                          ":","{","}");

$dataNow = explode($delimiterArray[0],str_replace(
        $delimiterArray,$delimiterArray[0], $restData));

$messageArray = explode(":",$messageString,2);
$message = substr($messageArray[1],0,strrpos($messageArray[1],"}"));


  $depDate = explode(",",$dataNow[3]);
  $depDate =  $depDate[0];
  $addressFrom=str_replace(","," ",$dataNow[11]);
  $latFrom = explode(",",$dataNow[13]);
  $latFrom = $latFrom[0];
  $longFrom = $dataNow[15];

  $addressTo= str_replace(","," ",$dataNow[21]);
  $latTo = explode(",",$dataNow[23]);
  $latTo = $latTo[0];
  $longTo = $dataNow[25];

  $TripLength = $dataNow[28];

  $spots = explode(",",$dataNow[31]);
  $spots = $spots[0];

  echo "**********Spots-----------------$spots";
  $email = $_SESSION['email'];




  $women_only = explode(",",$dataNow[33]);
  $women_only = $women_only[0];


  $DriverIDQuery = queryMysql("SELECT userID FROM $users_table WHERE emailAddress='$email'");

  // Driver Query if Statement

  if (!$DriverIDQuery)
                {
                    die('Error: ' . mysql_error());
                 }
  else{

    // Database Queries if Statement 

  if (mysql_num_rows($DriverIDQuery) == 0)
  {
    $error = "<span class='error'>
     <div class='well ds-component ds-hover' data-componentid='well1'>
               <div class='ds-component ds-hover' data-componentid='content2'>
                  <h1 style='text-align: center;'>Sorry!</h1>
                     <p style='text-align: center;'>This e-mail is not registered to Easy-Ride. Please, Make sure you register first. Thanks!</p>

                </div>  
             </div> </span><br /><br />";
    echo $error;
    }
  else{ 

 $DriverID = mysql_result($DriverIDQuery, 0);

  $TripQuery="INSERT INTO $Trip_Table (
               AddressFrom,
               AddressTo,
               TripDate,
               TripLength,
               WomenOnly,
               Spots,
               Message,
               DriverID
             
            ) VALUES(
               '$addressFrom',
               '$addressTo',
               '$depDate',
               '$TripLength',
               '$women_only',
               '$spots',
               '$message',
               '$DriverID'
                )";

 $coordsInfoQuery = "INSERT INTO $CoordinatesTable (
                        DriverID,
                        TripDate,
                        LatitudeFrom,
                        LongitudeFrom,
                        LatitudeTo ,
                        LongitudeTo

                      ) VALUES (

                      '$DriverID',
                      '$depDate',
                      '$latFrom',
                      '$longFrom',
                      '$latTo',
                      '$longTo'

                      ) ";

$addressQueryFrom = "INSERT INTO $addressTable (
               Address,
               TripDate,
               DriverID
             
            ) VALUES(
               '$addressFrom',
               '$depDate',
               '$DriverID'
                )";

$addressQueryTo = "INSERT INTO $addressTable (
               Address,
               TripDate,
               DriverID
             
            ) VALUES(
               '$addressTo',
               '$depDate',
               '$DriverID'
                )";

            if (!queryMysql($TripQuery) &!queryMysql($coordsInfoQuery)
              &!queryMysql($addressQueryFrom)&!queryMysql($addressQueryTo))
                {
                    die('Error: ' . mysql_error());
                 }
            else{

echo <<<_END
          
            <div class="well ds-component ds-hover" data-componentid="well1">
               <div class="ds-component ds-hover" data-componentid="content2">
                  <h1 style="text-align: center;">Success!</h1>
                     <p style="text-align: center;">You have successfully Added Your Trip Details to Easy Ride!</p>

                </div>  
             </div>
             
_END;
         }  // End of this if statement

      
      } // end of the Database Queries if Statement 

} // End of the Driver Query if Statement

}  // End of the main if statement, the one that deals with empty post requests.

            mysql_close($connection);



?>
