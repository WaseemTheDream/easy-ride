<?php

$nav_bar = array(
    "Search" => "/index.php",
    "Share" => "/share.php",
    "Trips" => "/trips.php");

function render_nav_bar() {
    global $nav_bar;
    foreach ($nav_bar as $item => $link) {
        $active = "";
        if ($link == $_SERVER['REQUEST_URI'])
            $active = "class='active'";
        echo "<li $active><a href='$link'>$item</a></li>";
    }
}

$register_active = "";
if ($_SERVER['REQUEST_URI'] == '/register.php')
    $register_active = "class='active'";
$not_logged_in = <<<XYZ
<ul class="nav pull-right">
  <li class="divider-vertical"></li>
  <li class="dropdown">
    <div class="nav-collapse collapse">
      <ul class="nav pull-right">
        <li $register_active><a href="/register.php">Sign Up</a></li>
        <li>
          <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In<strong class="caret"></strong></a>
          <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
            <form method="post" action="/functions/login.php" accept-charset="UTF-8">
              <input id="login-email" style="margin-bottom: 15px;" type="text" name="login-email" size="30" placeholder="Email Address" />
              <input id="login-password" style="margin-bottom: 15px;" type="password" name="login-password" size="30" placeholder="Password" />
              <div><input id="remember-me" style="float: left; margin-right: 10px;" type="checkbox" name="remember-me" value="1" />
              <label class="string optional" for="user_remember_me"> Remember me</label></div>
              <input class="btn btn-primary span2" type="submit" name="submit" value="Sign In" />
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
        echo '<p class="navbar-text pull-right" id="logged-in">';
        $first_name = $_SESSION['first_name'];
        echo "Logged in as $first_name.</p>";
    } else {
        global $not_logged_in;
        echo $not_logged_in;
    }
}
?>
<script src="/js/lib/bootstrap-dropdown.js"></script>
<script src="/js/common/navbar.js"></script>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="brand" href="/index.php">Easy Ride</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <?php render_nav_bar(); ?>
        </ul>
         <?php user_status(); ?>
      </div>
    </div>
  </div>
</div>