<?php 
  include "templates/head.php";
  include "functions/user.php";
?>
<?php if (user\user_logged_in()): ?>
<link href="/css/trips.css" rel="stylesheet">
<secton id="admin">
<div class="container container-fluid">
  <header id="admin-header-driving">
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
          <em id="trips-driving-msg" style="display: none;">There are no upcoming trips for which you are driving.</em>
        </td>
      </tr>
    </tbody>
  </table>


  <header id="admin-header-riding">
    <h2>Riding</h2>
    <p>Upcoming trips for which you are riding with some else.</p>
  </header>
  <table class="table table-bordered table-hover trips" id="trips-riding-table">
    <thead>
      <tr>
        <th class="departure">Departure</th>
        <th class="origin">Origin</th>
        <th class="destination">Destination</th>
        <th class="status">Request Status</th>
      </tr>
    </thead>
    <tbody id="trips-riding">
      <tr>
        <td id="trips-riding-status" colspan="5">
          <img id="trips-riding-loader" class="loader" src="/img/ajaxloader.gif">
          <em id="trips-riding-msg" style="display: none;">There are no upcoming trips for which you are riding with someone.</em>
        </td>
      </tr>
    </tbody>
  </table>

</div>
</secton>
<div id="modal-ride-requests" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="manage-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="manage-label">Ride Requests</h3>
        <p>Use this dialog to approve or deny ride requests made by other users.</p>
    </div>
    <div class="modal-body">
        <div id="modal-ride-requests-spots-remaining"><strong>Spots Remaining: </strong><span id="modal-ride-requests-spots-remaining-value">0</span></div>
        <form class="form-horizontal" id="modal-ride-requests-form"  method="post">
        </form>
        <div id="modal-ride-requests-status">
          <img id="modal-ride-requests-loader" class="loader" src="/img/ajaxloader.gif">
          <em id="modal-ride-requests-msg" style="display: none;">There are no upcoming trips for which you are driving.</em>
        </div>
    </div>
    <!--  Form Actions -->
    <div class="modal-footer">
      <button type="reset" class="btn" data-dismiss="modal">Close</button>
    </div>
</div>
<script type="text/template" id="trip-row-template">
  <tr id="trip-<%= id %>">
    <td class="departure"><%= departure_string %></td>
    <td class="origin"><%= origin.address %></td>
    <td class="destination"><%= destination.address %></td>
    <td class="riders"><span class="badge badge-info"><%= spots_taken %></span></td>
    <td class="action">
      <div class="btn-group">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown-menu"><i class="icon icon-white icon-road"></i> Manage Trip <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a class="button-ride-requests" data-trip-id="<%= id %>"><i class="icon icon-user"></i> Ride Requests</a></li>
          <li class="divider"></li>
          <li><a><i class="icon-trash"></i> Delete</a></li>
        </ul>
      </div>
    </td>
  </tr>
</script>
<script type="text/template" id="rider-request-template">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" href="#accordion-rider-<%= rider.id %>">
        <label for="rider-<%= rider.id %>"><i class="icon icon-user"></i> <%= rider.first_name %> <%= rider.last_name %></label>
      </a>
      <div class="btn-group" id="rider-<%= rider.id %>-actions">
        <button data-action="DECLINED" data-id="<%= rider.id %>" type="button" class="btn btn-primary <% if (status=='DECLINED') { %>active<% } %>">Decline</button>
        <button data-action="PENDING" data-id="<%= rider.id %>" type="button" class="btn btn-primary <% if (status=='PENDING') { %>active<% } %>">Ignore</button>
        <button data-action="APPROVED" data-id="<%= rider.id %>" type="button" class="btn btn-primary <% if (status=='APPROVED') { %>active<% } %>">Approve</button>
      </div>
    </div>
    <div id="accordion-rider-<%= rider.id %>" class="accordion-body collapse">
      <div class="accordion-inner">
        <strong><i class="icon icon-comment"></i> Message: </strong><%= message %><br>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="ride-row-template">
  <tr id="ride-<%= id %>">
    <td class="departure"><%= departure_string %></td>
    <td class="origin"><%= origin.address %></td>
    <td class="destination"><%= destination.address %></td>
    <td class="riders">
      <% if (status=='DECLINED')  { %> <span class="label label-important">Request Declined</span> <% } %>
      <% if (status=='PENDING')  { %> <span class="label label-info">Request Pending</span> <% } %>
      <% if (status=='APPROVED')  { %> <span class="label label-info">Request Approved</span> <% } %>
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