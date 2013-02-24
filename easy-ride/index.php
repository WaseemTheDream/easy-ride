<!DOCTYPE html>
<html lang="en">
  <?php 
    include "head.php";
    include "navbar.php";
  ?>
  <link href="css/datepicker.css" rel="stylesheet">
  <link href="css/home.css" rel="stylesheet">
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
            <div class="control-group">
              <label class="control-label">From</label>
              <div class="controls">
                <input class="input-xlarge" id="search-from" type="text" placeholder="Type an address or zip code over here">
              </div>
            </div>

            <!-- To -->
            <div class="control-group">
              <label class="control-label">To</label>
              <div class="controls">
                <input class="input-xlarge" id="search-to" type="text" placeholder="Type an address or zip code over here">
              </div>
            </div>

            <!-- DepartureDate -->
            <div class="control-group">
              <label class="control-label">Date </label>
              <div class="controls">
                <div class="input-append date" id="search-departure-date" data-date="22-02-2013" data-date-format="dd-mm-yyyy">
                 <input class="span8" size="16" type="text" >
                   <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
              </div>
            </div>
          
            <!-- WomenOnly -->
            <div class="control-group" >
             <label class="checkbox">
              <div class="controls">
               <input type="checkbox" id="search-women-only"> Women Only
               </div>
             </label>
            </div>

           <!-- ShareButton -->
           <div class="form-actions" >
             <button type="button" id="search-button" class="btn btn-primary">Search</button>
           </div>
           </fieldset>
          </form><!-- End Sidebar Content -->
        </div>
        <div id="map_canvas" class="well container span8" style="float: right; height: 500px;"></div>
      </div>
    </div>
    
    
  <hr>
    <?php include "footer.php"?>
    </div>
  </body>
</html>
