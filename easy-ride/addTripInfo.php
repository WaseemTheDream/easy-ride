<?php
include 'head.php';
include 'navbar.php';
include_once 'functions.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$coordsFrom = array();
$coordsFrom = array('lat' => 0, 'lng' => 0);

$coordsTo  = array();
$coordsTo  = array('lat' => 0, 'lng' => 0);

if ($_POST)
{

   $addressFrom = sanitizeString($_POST['search-from']);
   $addressTo= sanitizeString($_POST['search-to']);
   $email = sanitizeString($_POST['email']);
   $Depdate = sanitizeString($_POST['search-departure-date']);
   $departure_time = sanitizeString($_POST['search-departure-time']);
   $TripLength = sanitizeString($_POST['search-arrival-time']);
   $Spots = sanitizeString($_POST['Spots']);
   $Women_Only = sanitizeString($_POST['Women_Only']);
   $message = sanitizeString($_POST['message']); 

  $coordsFrom = geocode($addressFrom);
  $coordsTo = geocode($addressTo);

  $latFrom = $coordsFrom['lat'];
  $longFrom= $coordsFrom['lng'];

  $latTo = $coordsTo['lat'];
  $longTo = $coordsTo['lng'];

  $DriverIDQuery = queryMysql("SELECT userID FROM $users_table WHERE EmailAddress='$email'");

  if (!$DriverIDQuery)
                {
                    die('Error: ' . mysql_error());
                 }
  else{

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
               DepDate,
               DepartureTime,
               TripLength,
               WomenOnly,
               Spots,
               Message,
               DriverID
            ) VALUES(
               '$addressFrom',
               '$addressTo',
               '$Depdate',
               '$departure_time',
               '$TripLength',
               '$Women_Only',
               '$Spots',
               '$message',
               '$DriverID'
                )";

  $cordsQuery = "INSERT INTO $CoordinatesTable (
                DriverID ,
                DepartureDate,
                DepartureTime ,
                LatitudeFrom ,
                LongitudeFrom ,
                LatitudeTo ,
                LongitudeTo 

            ) VALUES(
                '$DriverID',
                '$Depdate',
                '$departure_time',
                '$latFrom',
                '$longFrom',
                '$latTo',
                '$longTo'
                )";

            if (!queryMysql($TripQuery)&!queryMysql($cordsQuery))
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
            }
            mysql_close($connection);
}

}

}

function geocode($address)
{
  $address = urlencode($address);
  $url = "http://maps.google.com/maps/geo?output=xml&q=$address";

  $delay = 0;
  $geocode_pending = true;
  
  // load file from url
  while($geocode_pending)
  {
    try
    {
      $xml = simplexml_load_file($url);
    }
    catch(Exception $e)
    {
      // return an empty array for a file request exception
      return array();
    }
    
    //get response status
    $status = $xml->Response->Status->code;

    if (strcmp($status, '200') == 0)
    {
      $geocode_pending = false;

      // get coordinates node from xml response
      $coordsNode = explode(',', $xml->Response->Placemark->Point->coordinates);
      $coords['lat'] = $coordsNode[1];
      $coords['lng'] = $coordsNode[0];
     
    }
    
    // handle timeout responses and delay re-execution of geocoding
    else if (strcmp($status, 620) == 0)
    {
      $delay += 100000;
    }
    
    usleep($delay);
  }
   
  return $coords;  

}

?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8" />
    <title>Google Maps JavaScript API Example:  Extraction of Geocoding Data</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeuwEG1p2ewZFCY6Xt5pHKuBlOElPpUVw&sensor=true"></script>
    
    <!--<script src="//maps.google.com/maps?file=api&amp;v=2.x&amp;key=AIzaSyAeuwEG1p2ewZFCY6Xt5pHKuBlOElPpUVw&sensor=true" 
            type="text/javascript"></script> -->
    <script type="text/javascript">

    var map;
    var geocoder;

    function initialize() {
      map = new GMap2(document.getElementById("map_canvas"));
      map.setCenter(new GLatLng(34, 0), 1);
      geocoder = new GClientGeocoder();
    }

    // addAddressToMap() is called when the geocoder returns an
    // answer.  It adds a marker to the map with an open info window
    // showing the nicely formatted version of the address and the country code.
    function addAddressToMap(response) {
      map.clearOverlays();
      if (!response || response.Status.code != 200) {
        alert("Sorry, we were unable to geocode that address");
      } else {
        place = response.Placemark[0];
        point = new GLatLng(place.Point.coordinates[1],
                            place.Point.coordinates[0]);


        marker = new GMarker(point);
        map.addOverlay(marker);
        marker.openInfoWindowHtml(place.address + '<br>' +
          '<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);
      }
    }

    
    // showLocation() is called when you click on the Submit button
    // in the form.  It geocodes the address entered into the form
    // and adds a marker to the map at that location.
    var addressFrom= $addressFrom;

    function showLocation() {
      geocoder.getLocations(addressFrom, addAddressToMap);
      
    }

    </script>

    <style type="text/css">
      form label {display:block;}
      form fieldset {border: 1px solid #ccc;}
      div#container {width: 500px; margin:0 auto;}
      div#map {margin-top:10px;width:500px; border: 1px solid #ccc;}
      div#details {width:500px;}
    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places">
    </script>
    <link href="css/datepicker.css" rel="stylesheet">
    <link href="css/timepicker.css" rel="stylesheet">
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-timepicker.js"></script>
    <script src="js/index.js"></script>
  </head>
  
  <body onload="initialize()">
    <div id="container">
      <form class="form-horizontal well"  id="search" method="Post" action= "<?php echo $_SERVER['PHP_SELF']?>" onsubmit="showLocation(); return false;">
      <fieldset >
        <legend>Please Enter Your Trip Info</legend>
      <p>

     <!-- Address From -->
        <div class="control-group">
          <label class="control-label" for="AddressFrom">From</label>
          <div class="controls">
          <input class="input-xlarge" id="search-from" name="search-from" type="text" placeholder="Type an address or zip code over here" value="<?php echo empty($_POST['search-from']) ? '' : $_POST['search-from']?>">
          </div>
        </div>


     <!-- Address To -->
        <div class="control-group">
          <label class="control-label" for="AddressTo">To</label>
          <div class="controls">
          <input class="input-xlarge" id="search-to" name="search-to" type="text" placeholder="Type an address or zip code over here" value="<?php echo empty($_POST['search-to']) ? '' : $_POST['search-to']?>">
          </div>
        </div>
        
    <!-- email -->
        <div class="control-group">
          <label class="control-label" for="email">Email</label>
          <div class="controls">
            <input type="text" class="input-large" id="email" name="email" value="<?php echo empty($_POST['email']) ? '' : $_POST['email']?>">
          </div>
        </div>

    <!--  Departure Date -->
         <div class="control-group">
                <label class="control-label" for="search-departure-date">Departure Date</label>
                <div class="controls">
                <div class="input-append date"  data-date="22-02-2013" data-date-format="dd-mm-yyyy">
                 <input   id="search-departure-date" type="text" class="input-large" name="search-departure-date" value="<?php echo empty($_POST['search-departure-date']) ? '' : $_POST['search-departure-date']?>">
                   <span class="add-on"><i class="icon-calendar"></i></span>
                 </div>
                </div>
               </div>

        <!--  Departure Time -->
        <div class="control-group">
          <label class="control-label" for="search-departure-time"> Departure Time:</label>
          <div class="controls">
            <div class="input-append bootstrap-timepicker">
            <input id="search-departure-time" type="text" class="input-large"  name="search-departure-time" value="<?php echo empty($_POST['search-departure-time']) ? '' : $_POST['search-departure-time']?>">
          <span class="add-on"><i class="icon-time"></i></span>
          </div>
        </div>
      </div>   

    <!-- Trip Time-->
        <div class="control-group">
          <label class="control-label" for="search-arrival-time">Length of Trip (in minutes)</label>
          <div class="controls">  
            <input id="search-arrival-time"  type="text" class="input-large"  name="search-arrival-time" value="<?php echo empty($_POST['search-arrival-time']) ? '' : $_POST['search-arrival-time']?>">
       <script type="text/javascript"> $('#search-departure-time').timepicker(); </script>
        </div>
      </div>
       
        
    <!-- Spots-->
        <div class="control-group">
          <label class="control-label" for="State">Spots in The Car</label>
          <div class="controls">
            <input type="text" class="input-large" id="Spots" name="Spots" value="<?php echo empty($_POST['Spots']) ? '' : $_POST['Spots']?>">
          </div>
        </div>    

    <!-- Women Only-->
    <div class="control-group">
          <label class="control-label" for="Women_Only">Women Only</label>
          <div class="controls">
            <label class="radio"><input type="radio" value="Y" name="Women_Only" id="Y">Yes</label>
                  <label class="radio"><input type="radio" value="N" name="Women_Only" id="N">No</label>
          </div>
        </div>
          
  <!-- Message -->
    <div class="control-group">
      <label class="control-label" for="message">Your Message</label>
      <div class="controls">
      <textarea name="message" id="message" rows="10" cols="80" value="<?php echo empty($_POST['message']) ? '' : $_POST['message']?>"> </textarea>
      </div>
  </div>    

  <!-- Submit button  -->
         
  <!--  Form Actions -->
    <div class="form-actions">
           <button type="submit" class="btn btn-primary" name="submit"> Submit</button>
            <button type="reset" class="btn">Cancel</button>
    </div>      
        
      </p>
          
  </fieldset>
    </form>
    <div id="map_canvas" class="well container span8" style="float: right; height: 500px;margin-bottom:20px;margin-top:10px;"></div>
    </div>
    <?php include 'footer.php'; ?>
  </body>
</html>
