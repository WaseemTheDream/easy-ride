<?php 
  include "templates/head.php";
  include "functions/user.php";
?>
<?php if (user\user_logged_in()): ?>
<link href="/css/admin_users_view.css" rel="stylesheet">
<secton id="admin">
<div class="container container-fluid">
  <header id="admin-header">
    <h2>Easy-Ride Users</h2>
    <p>All of the easy-ride users are listed below. </p>
    <a href="/register.php"><i class='icon-plus'></i> Add New User</a>
  </header>
  <table class="table table-bordered table-hover admin-users_view" id="users_view-table">
    <thead>
      <tr>
        <th class="user_id">User ID</th>
        <th class="first_name">First Name</th>
        <th class="last_name">Last Name</th>
        <th class="e_mail">Email Address</th>
        <th class="drivers_license_id">Driver's License ID</th>
        <th class="gender">Gender</th>
      </tr>
    </thead>
    <tbody id="users_view">
      <tr>
        <td id="users_view_status" colspan="5">
          <img id="users_view_loader" class="loader" src="/img/ajaxloader.gif">
          <em id="users_view_msg">Users Table loading...</em>
        </td>
      </tr>
    </tbody>
  </table>
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
<script type="text/template" id="users_view_row_template">
<tr>
  <td class ="user_id"><%= user_id_string %></td>
  <td class="first_name"><%= first_name_string %></td>
  <td class="last_name"><%= last_name_string %></td>
  <td class="e_mail"><%= e_mail_string %></td>
  <td class="drivers_license_id"><%= drivers_license_id_string %></td>
  <td class="gender"><%= gender_string %></td>   
</tr>
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places"></script>
<script src="js/lib/underscore.min.js"></script>
<script src="js/admin_users_view.js"></script>
<?php else: ?>
  <div class="well ds-component ds-hover container-narrow" data-componentid="well1">
  <div class="ds-component ds-hover" data-componentid="content2">
  <?php functions\html_respond('Log In Required', 'Please register or log in to access this part of the website'); ?>
  </div>
  </div>
<?php endif; ?>
<?php include "templates/footer.php" ?>