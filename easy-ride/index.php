<!DOCTYPE html>
<html lang="en">
  <?php 
    include "head.php";
    include "navbar.php";
  ?>
  <body>
    <script src="js/index.js"></script>
	<body>
   
     <div class="container-fluid">
  			<div class="row-fluid">
    			<div class="container span4">
     				<!--Sidebar content-->
            <form class="form-horizontal well" id="search">
            <fieldset>
       				<!-- From -->
              <label>From</label>
              <input class="input-xlarge" type="text" placeholder="Type an address or zipcode over here.">

      				<div class="control-group">
      					<label class="control-label" for="from">From</label>
      					<div class="controls">
      						<input type="text" class="input-large" id="from" name="from">
                </div>
        				
              <!-- To -->
              <div class="control-group">
      					<label class="control-label" for="to">To</label>
      					<div class="controls">
      						<input type="text" class="input-large" id="to" name="to">
      					</div>
      				</div>
            </fieldset>
            </form><!-- End Sidebar Content -->

          </div>
        </div>
          
  			<div class="container span8">
   				 <!--Body content-->
  			</div>
			</div> 
		</div>
		
    
	<hr>
    <?php include "footer.php"?>
    </div>
  </body>
</html>
