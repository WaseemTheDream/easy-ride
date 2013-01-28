<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Easy Ride</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              <?php
                session_start();
                if (isset($_SESSION['email'])) {
                  echo "Logged in as " . $_SESSION['logged_in'];
                } else {
                  echo "<a href='login.php' class='navbar-link'>Click here to log in.</a>";
                }
              ?>
            </p>
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
              <li><a>About</a></li>
              <li><a href="/register.php">Register</a></li>
              <li><a>Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
  </div>
</div>