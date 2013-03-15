<?php 
  include "templates/head.php";
  include "functions/user.php";
?>
<?php if (user\user_logged_in()): ?>
<link href="/css/trips.css" rel="stylesheet">
<secton id="admin">
<div class="container container-fluid">
  <header id="admin-header">
    <h2>Driving</h2>
    <p>Upcoming trips for which you are driving. Use this section to approve or deny ride requests, manage, and plan your trip details.</p>
    <a href="/share.php"><i class='icon-plus'></i> New Trip</a>
  </header>
  <table class="table table-bordered table-hover trips" id="trips-driving-table">
    <thead>
      <tr>
        <th class="departure">Departure</th>
        <th class="origin">Origin</th>
        <th class="destination">Destination</th>
        <th class="riders">Riders</th>
        <th class="action">Action</th>
      </tr>
    </thead>
    <tbody id="trips-driving">
      <tr>
        <td id="trips-driving-status" colspan="5">
          <img id="trips-driving-loader" class="loader" src="/img/ajaxloader.gif">
          <em id="trips-driving-msg">There are no upcoming trips for which you are driving.</em>
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
<script type="text/template" id="trip-row-template">
<tr>
  <td class="departure"><%= departure_string %></td>
  <td class="origin"><%= origin.address %></td>
  <td class="destination"><%= destination.address %></td>
  <td class="riders"><span class="badge badge-info"><%= spots %></span></td>
  <td class="action">
    <div class="btn-group">
      <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown-menu"><i class="icon icon-white icon-road"></i> Manage Trip <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <li><a><i class="icon icon-user"></i> Ride Requests</a></li>
        <li class="divider"></li>
        <li><a><i class="icon-trash"></i> Delete</a></li>
      </ul>
    </div>
  </td>
</tr>
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places"></script>
<script src="js/lib/underscore.min.js"></script>
<script src="js/trips.js"></script>
<?php else: ?>
  <div class="well ds-component ds-hover container-narrow" data-componentid="well1">
  <div class="ds-component ds-hover" data-componentid="content2">
  <?php functions\html_respond('Log In Required', 'Please register or log in to access this part of the website'); ?>
  </div>
  </div>
<?php endif; ?>
<?php include "templates/footer.php" ?>