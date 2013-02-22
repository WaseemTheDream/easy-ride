<!DOCTYPE html>
<html lang="en">
  <?php 
    include "head.php";
    include "navbar.php";
  ?>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places">
            // src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeuwEG1p2ewZFCY6Xt5pHKuBlOElPpUVw&sensor=true">
  </script>
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
            </fieldset>
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
