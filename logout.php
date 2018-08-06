<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 27-06-2018
	Programming Language: php
	Purpose: User is log out from the application and all the session will be destory from the application.
-->

<?php
  // to destory to all application session like access token.
  session_start();
  session_destroy();
?>

<html>
  <head>
    <title>Index</title>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/logout.css">
  </head>

  <body class="background-img">
    <div class="container" style="margin-top:180px">
  		<div class="row">
  			<div class="col-xs-12">
  				<div class="form-group">
  					<a href="login.php" class="btn btn-default btn-block btn-lg"> Log In </a>
  				</div>
  			</div>
  		</div>
    </div>
  </body>
</html>
