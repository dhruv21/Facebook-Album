<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 30-06-2018
	Programming Language: php
	Purpose: It is used when user is not login with the google drive account. it will redirect to the goole drive account.
-->

<?php
	require_once "g-config.php";

  if (isset($_SESSION['g_access_token'])) {
		header('Location: upload-album.php');
		exit();
	}

	$loginURL = $gClient->createAuthUrl();
?>
<html>
  <head>
    <title>Index</title>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/google-login.css">
  </head>


  <body class="background-img">
    <div class="container">

      <div class="row" style="margin-top: 50px">
        <div class="col-md-6 col-md-offset-3">
        	<input type="button" onclick="javascript:window.location = '<?php echo $loginURL ?>';" value="Log in with Google Drive" class="animated zoomIn btn btn-lg btn-danger btn-block">
        </div>
      </div>

      <div class="row" style="margin-top: 50px">
        <div class="col-md-6 col-md-offset-3">
          <a href="index.php" class="btn btn-block btn-lg btn-default animated zoomIn"> Home </a>
        </div>
      </div>
    </div>
  </body>
</html>
