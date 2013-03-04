<?php

$not_logged_in = <<<XYZ
<ul class="nav pull-right">
  <li class="divider-vertical"></li>
  <li class="dropdown">
    <div class="nav-collapse">
            <ul class="nav pull-right">
            <li>
    <a href="register.php">Sign Up</a>
    </li>
    <li>

      <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
      <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
        <form method="post" action="/functions/login.php" accept-charset="UTF-8">
          <input id="login-email" style="margin-bottom: 15px;" type="text" name="login-email" size="30" placeholder="Email Address" />
          <input id="login-password" style="margin-bottom: 15px;" type="password" name="login-password" size="30" placeholder="Password" />
          <input id="remember-me" style="float: left; margin-right: 10px;" type="checkbox" name="remember-me" value="1" />
          <label class="string optional" for="user_remember_me"> Remember me</label>
          <input class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="submit" value="Sign In" />
        </form>
      </div>
      </li>
      </ul>
      </div>
  </li>
</ul>
XYZ;

function user_status() {
    if (isset($_SESSION['user_id'])) {
        echo '<ul class="nav pull-right"><li><a href="/functions/logout.php">Logout</a></li></ul>';
        echo '<p class="navbar-text pull-right">';
        $first_name = $_SESSION['first_name'];
        echo "Logged in as $first_name.</p>";
    } else {
        global $not_logged_in;
        echo $not_logged_in;
    }
}
?>
<script src="/js/common/bootstrap-dropdown.js"></script>
<script src="/js/common/navbar.js"></script>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="/index.php">Easy Ride</a>
      <div class="nav-collapse">
        <ul class="nav">
          <li class="active"><a href="/index.php">Search</a></li>
          <li><a href="/share.php">Share</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <?php user_status(); ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>