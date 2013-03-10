<?php 
  include "templates/head.php";
?>
<secton id="admin">
<div class="well container-fluid container-narrow">
    <header id="admin-header">
        <h2>Admin Panel</h2>
        <p>Use this panel to create, view, update, or delete existing users of the website.</p>
        <a data-toggle="modal" href="#modal-add"><i class='icon-plus'></i> Add User</a>
    </header>
    <section id="admin-main">
        <ul id="user-list"></ul>
    </section>
    <footer id="admin-footer"></footer>
</div>
</secton>
<div id="modal-add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="add-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="ranked-label">User Details</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="register"  method="post" action="register_post.php">
          <fieldset>
            <!-- First Name -->
            <div class="control-group">
              <label class="control-label" for="register-first-name">First Name</label>
              <div class="controls">
                <input type="text" class="input-large" id="register-first-name" name="register-first-name">
              </div>
            </div>
            
            <!-- Last Name -->
            <div class="control-group">
              <label class="control-label" for="register-last-name">Last Name</label>
              <div class="controls">
                <input type="text" class="input-large" id="register-last-name" name="register-last-name">
              </div>
            </div>
            
            <!-- Email Address -->
            <div class="control-group">
              <label class="control-label" for="register-email">Email Address</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="register-email" name="register-email">
              </div>
            </div>
            
            <!--  Password -->
            <div class="control-group">
              <label class="control-label" for="register-password">Password</label>
              <div class="controls">
                <input type="password" class="input-medium" id="register-password" name="register-password">
              </div>
            </div>
            
            <!-- Driver's License ID -->
            <div class="control-group">
              <label class="control-label" for="register-drivers-license-id">Driver's License ID</label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="register-drivers-license-id" name="register-drivers-license-id">
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
          </fieldset>
        </form>
    </div>
    <!--  Form Actions -->
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" name="submit">Save</button>
      <button type="reset" class="btn">Cancel</button>
    </div>
</div>
<?php include "templates/footer.php" ?>