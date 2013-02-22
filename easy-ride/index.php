<!DOCTYPE html>
<html lang="en">
  <?php 
    include "head.php";
    include "navbar.php";
  ?>
  <link href="css/datepicker.css" rel="stylesheet">
  <link href="css/timepicker.css" rel="stylesheet">
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places">
            // src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeuwEG1p2ewZFCY6Xt5pHKuBlOElPpUVw&sensor=true">
  </script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/bootstrap-timepicker.js"></script>
  <script src="js/index.js"></script>
  <body>
   
     <div class="container-fluid">
        <div class="row-fluid">
          <div class="container span4">
            <!--Sidebar content-->
            <form class="form-horizontal well" id="search">
            <fieldset>
              <!-- From -->
              <div class="form-input">
                <label>From</label>
                <input class="input-xlarge" id="search-from" type="text" placeholder="Type an address or zip code over here">
              </div>      
              <!-- To -->
              <div class="form-input">
                <label>To</label>
                <input class="input-xlarge" id="search-to" type="text" placeholder="Type an address or zip code over here">
              </div>
              <!-- DepartureDate -->
              <div class="form-input"
                <label>Date </label>
                <div class="input-append date" id="search-departure-date" data-date="22-02-2013" data-date-format="dd-mm-yyyy">
                 <input class="span8" size="16" type="text" >
                   <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
              </div>
              <!-- DepartureTime -->
              <div class="form-input"
               <label>Departure Time: </label>
                <div class="input-append bootstrap-timepicker" id="search-departure-time">
                 <input id="timepicker1" type="text" class="input-small">
                 <span class="add-on"><i class="icon-time"></i></span>
                </div>
              </div>
              <!-- ArrivalTime -->
              <div class="form-input"
               <label>Arrival Time: </label>
                <div class="input-append bootstrap-timepicker" id="search-arrival-time">
                 <input id="timepicker2" type="text" class="input-small">
                 <span class="add-on"><i class="icon-time"></i></span>
                </div>
              </div>       
              <script type="text/javascript"> $('#timepicker1').timepicker(); </script>
              <script type="text/javascript"> $('#timepicker2').timepicker(); </script>
            </fieldset>
              <!-- WomenOnly -->
              <div class="form-input" >
               <label class="checkbox">
                 <input type="checkbox" id="search-woman-only"> Women Only
               </label>
              </div>
             <!-- Message -->
             <div class="form-input" >
                <textarea id="search-message" rows="3" placeholder="Message..."></textarea>
             </div>
             <!-- ShareButton -->
             <div class="form-input" >
               <button type="button" id="search-share-button" class="btn">Share</button>
             </div>
            </form><!-- End Sidebar Content -->
          </div>
          <d$iv id="map_canvas" class="well container span8" style="float: right; height: 500px;"></div>
        </div>
      </div>
    </div> 
  </div>
    
    
  <hr>
    <?php include "footer.php"?>
    </div>
  </body>
</html>
