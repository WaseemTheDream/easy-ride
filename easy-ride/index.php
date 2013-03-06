<?php 
  include "templates/head.php";
?>
<link href="css/common/datepicker.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
 <div class="container-fluid">
    <div class="row-fluid">
      <div id="search-bar">
        <!--Sidebar content-->
        <form class="form-horizontal well" id="search">
        <fieldset>

          <!-- From -->
          <div class="control-group">
            <label class="control-label">From</label>
            <div class="controls">
              <input class="input-xlarge" id="search-from" type="text" placeholder="Type the name of a place or address over here">
            </div>
          </div>

          <!-- To -->
          <div class="control-group">
            <label class="control-label">To</label>
            <div class="controls">
              <input class="input-xlarge" id="search-to" type="text" placeholder="Type the name of a place or address over here">
            </div>
          </div>

          <!-- Departure Date -->
          <div class="control-group">
            <label class="control-label">Date</label>
            <div class="controls">
              <div class="input-append date" id="search-departure-date" data-date="22-02-2013" data-date-format="dd-mm-yyyy">
               <input class="span8" size="16" type="text" >
                 <span class="add-on"><i class="icon-calendar"></i></span>
              </div>
            </div>
          </div>
        
          <!-- Women Only -->
          <div class="control-group" >
           <label class="checkbox">
            <div class="controls">
             <input type="checkbox" id="search-women-only"> Women Only
             </div>
           </label>
          </div>

         <!-- Search Button -->
         <div class="form-actions" >
           <button type="button" id="search-button" class="btn btn-primary">Search</button>
         </div>
         </fieldset>
        </form><!-- End Sidebar Content -->
      </div>
      <div id="map_canvas" class="well"></div>
    </div>
  </div>
<hr>
<!-- Load JS in the end for faster page loading -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places"></script>
<script src="js/common/bootstrap-datepicker.js"></script>
<script src="js/common/bootstrap-timepicker.js"></script>
<script src="js/index.js"></script>
<?php include "templates/footer.php" ?>
