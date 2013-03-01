<!DOCTYPE html>
<html lang='en'>
	<?php 
	include 'head.php'; 
	include 'navbar.php'; 
	?>
 	
	<script src="js/register.js"></script>
	<body>

 
	<div class="container-narrow">
      <!-- Main hero unit for a primary marketing message or call to action -->
        <form class="form-horizontal well" id="register"  method="post" action="register_post.php">
			<fieldset>
				<legend>Registration Form</legend>
				
				<!-- First Name -->
				<div class="control-group">
					<label class="control-label" for="register-first-name">First Name</label>
					<div class="controls">
						<input type="text" class="input-large" id="register-first-name">
					</div>
				</div>
				
				<!-- Last Name -->
				<div class="control-group">
					<label class="control-label" for="register-last-name">Last Name</label>
					<div class="controls">
						<input type="text" class="input-large" id="register-last-name">
					</div>
				</div>
				
				<!-- Email Address -->
				<div class="control-group">
					<label class="control-label" for="register-email">Email Address</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="register-email">
					</div>
				</div>
				
				<!--  Password -->
				<div class="control-group">
					<label class="control-label" for="register-password">Password</label>
					<div class="controls">
						<input type="password" class="input-medium" id="register-password">
					</div>
				</div>
				
				<!--  Repeat Password -->
				<div class="control-group">
					<label class="control-label" for="register-repeat-password">Repeat Password</label>
					<div class="controls">
						<input type="password" class="input-medium" id="register-repeat-password">
					</div>
				</div>
				
				<!-- Driver's License ID -->
				<div class="control-group">
					<label class="control-label" for="register-driver-license-id">Driver's License ID</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="register-driver-license-id">
					</div>
				</div>
				
				<!--  Gender -->
				<div class="control-group">
					<label class="control-label" for="register-gender">Gender</label>
					<div class="controls">
						<label class="radio"><input type="radio" value="male" name="register-gender" id="register-male">Male</label>
            			<label class="radio"><input type="radio" value="female" name="register-gender" id="register-female">Female</label>
					</div>
				</div>
			
				<!-- Submit button  -->
				 
				<!--  Form Actions -->
				<div class="form-actions">
            		<button type="submit" class="btn btn-primary" name="submit"> Submit</button>
            		<button type="reset" class="btn">Cancel</button>
          		</div>
			</fieldset>
		</form>
      <?php include "footer.php" ?>
    </div>
  </body>
</html>