<!DOCTYPE html>
<html lang='en'>
 <h3>Please enter your details to log in</h3>
 <body>
 <div>
 
<?php 
  include "head.php";
  include "navbar.php";
  ?>
 <form method="post" action="loginResponse.php">
 	
    <div class="control-group">
    	<label for="email">
     		 Email
   		 </label>
      <input name="email" type="email">
    </div>
    <div class="control-group">
    	<label for="password">
      Password 
    </label>
      <input name="password" type="password">
    </div>

    <div class="form-actions">
            		<button type="submit" class="btn " name="submit" href="#"> Signin</button>
        
  </form>
</div>
<br />
<?php 
  include "footer.php";
  ?>
</body>
</html>