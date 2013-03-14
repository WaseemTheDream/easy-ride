<?php 
  include "templates/head.php";
?>
<link href="css/lib/bootstrap-datepicker.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
 <div class="container-fluid">
    <div class="row-fluid">
      <div id="search-bar">
        <!--Sidebar content-->
        <div class="form-horizontal well" id="search">
        <fieldset>
          <div class="control-group" id="search-route">
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
          </div>

          <!-- Departure Date -->
          <div class="control-group" id="search-departure">
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
           <button type="button" id="search-button" class="btn btn-primary"><i class="icon icon-white icon-search"></i> Search</button>
         </div>
         </fieldset>
         </form>
        </div><!-- End Sidebar Content -->
        <div class="well" id="search-results">
          <h4>Search Results</h4>
          <div id="trips"></div>
        </div>
      </div>
      <div id="map_canvas" class="well"></div>
    </div>
  </div>
<hr>

<!-- Request Ride Modal -->
<div id="modal-request-ride" class="modal hide fade">
    <div class="modal-header">
      <a data-dismiss="modal" href="#" class="close">&times;</a>
      <h3>Request Ride</h3>
    </div>
    <div class="modal-body">
      <div id="modal-trip-info">
      </div>
      <form class="form-horizontal" method="post" action="register_post.php">
        <fieldset>
          <!-- Message -->
          <div class="control-group">
            <div class="controls">
              <textarea id="modal-trip-request-message" class="input-block-level" rows="5" placeholder="Request Message..."></textarea>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#" id='modal-request-ride-submit' class="btn btn-primary">Request</a>
      <a data-dismiss="modal" class="btn secondary">Cancel</a>
    </div>
</div>

<!-- Load JS in the end for faster page loading -->
<script type="text/template" id="trip-template">
<div class="accordion-group trip-info" id="trip-<%= id %>">
  <div class="accordion-heading">
    <a class="accordion-toggle" data-toggle="collapse" data-parent="#trip-<%= id %>" href="#collapse-<%= id %>">
      <strong><i class="icon icon-map-marker"></i> From: </strong><%= origin.address %><br>
      <strong><i class="icon icon-map-marker"></i> To: </strong><%= destination.address %><br>
      <strong><i class="icon icon-time"></i> Departure: </strong><%= departure_string %>
    </a>
  </div>
  <div id="collapse-<%= id %>" class="accordion-body collapse">
    <div class="accordion-inner">
      <strong><i class="icon icon-user"></i> Driver: </strong><%= driver.first_name %> <%= driver.last_name %><br>
      <strong><i class="icon icon-road"></i> Trip Length: </strong><%= length %><br>
      <strong><i class="icon icon-tasks"></i> Spots Remaining: </strong><%= spots %><br>
      <strong><i class="icon icon-comment"></i> Message: </strong><%= message %><br>
      <button type="button" class="btn btn-small btn-primary" id="request-trip-<%= id %>"><i class='icon icon-envelope icon-white'></i> Request Ride</button>
    </div>
  </div>
</div>
</script>
<script type="text/template" id="trip-modal-template">
  <strong><i class="icon icon-map-marker"></i> From: </strong><%= origin.address %><br>
  <strong><i class="icon icon-map-marker"></i> To: </strong><%= destination.address %><br>
  <strong><i class="icon icon-time"></i> Departure: </strong><%= departure_string %><br>
  <strong><i class="icon icon-user"></i> Driver: </strong><%= driver.first_name %> <%= driver.last_name %><br>
  <strong><i class="icon icon-road"></i> Trip Length: </strong><%= length %><br>
  <strong><i class="icon icon-tasks"></i> Spots Remaining: </strong><%= spots %><br>
  <strong><i class="icon icon-comment"></i> Message: </strong><%= message %><br>
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places"></script>
<script src="js/lib/underscore.min.js"></script>
<script data-main="js/index.js" src="js/require.js"></script>
<?php include "templates/footer.php" ?>
