<!DOCTYPE html>
<html lang="en">
	<?php include "head.php"?>
	<script src="js/create.js"></script>
	<body>
	<?php  include "navbar.php"?>
	<div class="container-narrow">
      <!-- Main hero unit for a primary marketing message or call to action -->
        <form class="form-horizontal well" id="register" action="register.php" method="post">
			<fieldset>
				<legend>Registration Form</legend>
				
				<!-- First Name -->
				<div class="control-group">
					<label class="control-label" for="first-name">First Name</label>
					<div class="controls">
						<input type="text" class="input-large" id="first-name" name="first-name">
					</div>
				</div>
				
				<!-- Last Name -->
				<div class="control-group">
					<label class="control-label" for="last-name">Last Name</label>
					<div class="controls">
						<input type="text" class="input-large" id="last-name" name="last-name">
					</div>
				</div>
				
				<!-- Email Address -->
				<div class="control-group">
					<label class="control-label" for="email">Email Address</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="email" name="email">
					</div>
				</div>
				
				<!--  Password -->
				<div class="control-group">
					<label class="control-label" for="password">Password</label>
					<div class="controls">
						<input type="password" class="input-medium" id="password" name="password">
					</div>
				</div>
				
				<!--  Repeat Password -->
				<div class="control-group">
					<label class="control-label" for="repeat-password">Repeat Password</label>
					<div class="controls">
						<input type="password" class="input-medium" id="repeat-password" name="repeat-password">
					</div>
				</div>
				
				<!-- Driver's License ID -->
				<div class="control-group">
					<label class="control-label" for="driver-license-id">Driver's License ID</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="driver-license-id" name="driver-license-id">
					</div>
				</div>
				
				<!--  Gender -->
				<div class="control-group">
					<label class="control-label" for="gender">Gender</label>
					<div class="controls">
						<label class="radio"><input type="radio" value="male" name="group">Male</label>
            			<label class="radio"><input type="radio" value="female" name="group">Female</label>
					</div>
				</div>
				
				<!--  Form Actions -->
				<div class="form-actions">
            		<button type="submit" class="btn btn-primary">Submit</button>
            		<button type="reset" class="btn">Cancel</button>
          		</div>
			</fieldset>
		</form>
      <?php include "footer.php"?>
    </div>
  </body>
</html>
