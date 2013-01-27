 
<div class="nav-collapse collapse">
            <p class="navbar-text pull-right">    
           
 <?php                     
            if (isset($_SESSION['email']))
              {
          ?>
               <h3>Hello <?= $_SESSION['first-name'] ?>(<?= $_SESSION['id'] ?>)</h3>
              <h3> You are logged in. </h3>
            <a href="logout.php">logout</a>

            <?php
            }
            else{
             
             echo "<a href='register.php'>Register</a> or " .
                  "<a href='login.php' class='navbar-link'>Click here to log in.</a>";
            }
            ?>

             </p>
            <ul class="nav" >
              <li class="active"><a href="/index.php">Home</a></li>
              <li><a href="aboutUs.php">About</a></li>
               
             <li><a href= "ContactUs.php">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Easy Ride</a>
          
        </div>
	</div>
</div>