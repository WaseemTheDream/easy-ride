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
    			<div class="span4" style="background-color: grey;">
     				<!--Sidebar content-->
     				 
     				<!-- From -->
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
          </div>
        </div>
          
  			<div class="span8" style="background-color: grey;">
   				 <!--Body content-->
  			</div>
			</div> 
		</div>
		
    
	<hr>
    <?php include "footer.php"?>
    </div>
  </body>
</html>
