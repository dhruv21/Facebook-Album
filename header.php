<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 27-06-2018
	Programming Language: php
	Purpose: It will contains the navigation bar, user profile, user name and email address.
-->

<nav class="navbar navbar-default navbar-fixed-top animated bounceInDown">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <img src="assets/images/menu-button.png">
      </button>

      <a class="navbar-brand webName" href="login.php" id="nav-text-link"> <img src="assets/images/facebook-logo.png" id="logo"></a>
    </div>

    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="login.php" id="nav-text-link"><span class="glyphicon glyphicon glyphicon-home"></span> Home</a></li>
        <li><a href="download-album.php" id="nav-text-link"><span class="glyphicon glyphicon-cloud-download"></span> Download </a></li>
        <li><a href="upload-album.php" id="nav-text-link"><span class="glyphicon glyphicon-cloud-upload"></span> Upload </a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li> </li>
        <li><a href="logout.php" id="nav-text-link"><span class="glyphicon glyphicon-log-out"></span> Log out </a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container animated bounceInLeft">
  <div class="row" id="user-data-body">
    <div class="col-xs-2" id="user-img">
      <!-- Get the user profiel from the session. -->
      <img src="<?php echo $_SESSION['userData']['picture']['url']; ?>" class="img-responsive profile">
    </div>

    <div class="col-xs-8 col-md-offset-1" id="user-data">
      <!-- Get the user first name, last name and email address from the session. -->
      <h3 class="text-primary"><?php echo $_SESSION['userData']['first_name']; echo " ". $_SESSION['userData']['last_name'];?></h3>
      <p class="text-primary"> <?php if(isset($_SESSION['userData']['email'])){echo $_SESSION['userData']['email'];} ?> </p>
    </div>
  </div>
</div>
