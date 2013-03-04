<?php
session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

include 'login.php';

function login() {

  if (isset($_SESSION['fName'])) {
echo <<<_END

 <div class="nav-collapse">
         
        <ul class="nav pull-right">
        <li><a href="#">
_END;
    echo  "Welcome, ".$_SESSION['fName'];

echo <<<_END
        </a>
          </li>
          <li><a href="logout.php">Logout</a></li> 
        </ul>
      </div><!--/.nav-collapse -->

_END;

}
  else 
  {
    
echo <<<_END
<div class="nav-collapse">
        <ul class="nav pull-right">
        <li>
<a href="register.php">Sign Up</a>
</li>
<li>

  <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
  <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
    <form method="post" action="#" accept-charset="UTF-8">
      <input id="email" style="margin-bottom: 15px;" type="text" name="email" size="30" placeholder="Email Address" />
      <input id="password" style="margin-bottom: 15px;" type="password" name="password" size="30" placeholder="Password" />
      <input id="remember-me" style="float: left; margin-right: 10px;" type="checkbox" name="remember-me" value="1" />
      <label class="string optional" for="user_remember_me"> Remember me</label>
      <input class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="submit" value="Sign In" />
    </form>
  </div>
  </li>
  </ul>
  </div>
_END;
  }
}
?>
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/navbar.js"></script>
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
        <ul class="nav pull-right">
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <?php login(); ?>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>