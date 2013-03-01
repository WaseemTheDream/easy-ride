<!DOCTYPE html>
<html lang="en">
  <?php 
    include "head.php";
    include "navbar.php";
  ?>
  <link href="css/datepicker.css" rel="stylesheet">
  <link href="css/timepicker.css" rel="stylesheet">
  <link href="css/share.css" rel="stylesheet">
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places">
  </script>
  <script src="js/bootstrap.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/bootstrap-timepicker.js"></script>
  <script src="js/share.js"></script>
  <body>
   
   <div class="container-fluid">
      <div class="row-fluid">
        <div class="container span4">
          <!--Sidebar content-->
          <form action="share_post.php" method="post" class="form-horizontal well" id="share">
          <fieldset>

            <div id="share-route" class="control-group">
              <!-- From -->
              <div class="control-group">
                <label class="control-label">From</label>
                <div class="controls">
                  <input class="input-xlarge" id="share-from" type="text" placeholder="Type the name of a place or address over here">
                </div>
              </div>

              <!-- To -->
              <div class="control-group">
                <label class="control-label">To</label>
                <div class="controls">
                  <input class="input-xlarge" id="share-to" type="text" placeholder="Type the name of a place or address over here">
                </div>
              </div>
            </div>
            <!-- Departure -->
            <div class="control-group" id="share-departure">
              <label class="control-label">Departure</label>
              <div class="controls controls-row">
                <!-- Date Picker -->
                <div class="input-append date" id="share-departure-date" data-date="" data-date-format="dd-mm-yyyy">
                 <input class="span7" size="16" type="text" >
                   <span class="add-on"><i class="icon-calendar"></i></span>
                </div>

                <!-- Time Picker -->
                <div class="input-append bootstrap-timepicker">
                 <input id="share-departure-time" type="text" class="input-mini">
                 <span class="add-on"><i class="icon-time"></i></span>
                </div>
              </div>
            </div>
              
            <!-- Trip Length -->
            <div class="control-group">
              <label class="control-label">Trip Length</label>
              <div class="controls">
                <input class="input-medium" id="share-trip-length" type="text">
              </div> 
            </div>

          
               <!-- Spots-->
             <div class="control-group">
              <label class="control-label" for="share-spots">Spots in Your Car</label>
             <div class="controls">
              <input type="number" class="input-medium" id="share-spots" name="share-spots" min="1" max="49">
           </div>
             </div>

          
            <!-- Women Only -->
            <div class="control-group" >
             <label class="checkbox">
              <div class="controls">
               <input type="checkbox" id="share-women-only">Women Only
               </div>
             </label>
            </div>

           
           <!-- Message -->
           <div class="control-group">
            <label class="control-label">Message</label>
            <div class="controls">
              <textarea id="share-message" class="input-xlarge" rows="5" placeholder="Message..."></textarea>
            </div>
           </div>

           <!-- Share Button -->
           <div class="form-actions" >
             <button type="button" id="share-button" class="btn btn-primary">Share</button>
           </div>
           </fieldset>
          </form><!-- End Sidebar Content -->
        </div>
        <div id="map_canvas" class="well container span8" style="float: right; height: 500px;"></div>
      </div>
    </div>
  <hr>
    <?php include "footer.php"?>
  </body>
</html>
