<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 27-06-2018
	Programming Language: php
	Purpose: User can connect his/her FB account.
-->

<?php
	require_once "f-config.php";

	// To check user is login or not.
  if (isset($_SESSION['fb_access_token'])) {
		header('Location: login.php');
		exit();
	}

	$redirectURL = "http://localhost/rtcampFacebookPhotoGallery/f-callback.php";
	$permissions = ['email'];
	$loginURL = $helper->getLoginUrl($redirectURL, $permissions);

?>

<html>
  <head>
    <title>Index</title>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/index.css">
  </head>


  <body class="background-img">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <h3 class="text-center text-info animated rubberBand" id = "index-header">  Welcome to facebook Album Demo </h3>
        </div>
      </div>

      <div class="row" id="login-button">
        <div class="col-md-6 col-md-offset-3">
        	<input type="button" onclick="window.location = '<?php echo $loginURL ?>';" value="Log in with Facebook" class="animated zoomIn btn btn-lg btn-primary btn-block">
        </div>
      </div>
    </div>
  </body>
</html>
