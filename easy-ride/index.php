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
       		<div class="form-input">
              <label>From</label>
              <input class="input-xlarge" type="text" placeholder="Type an address or zip code over here">
        	</div>			
              		<!-- To -->
            <div class="form-input">
              <label>To</label>
              <input class="input-xlarge" type="text" placeholder="Type an address or zip code over here">
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
