<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 27-06-2018
	Programming Language: php
	Purpose: To show the user information and display the user albums in thumbnail.
-->

<?php
	require_once "f-config.php";

	// Check the user is login with the facebook or not.
	if (!isset($_SESSION['fb_access_token'])) {
		header('Location: index.php');
		exit();
	}
	else{
		// $response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large),albums{count,name,photos{images}}", $_SESSION['fb_access_token']);
		// $userData = $response->getGraphNode()->asArray();
	}
?>
<html>
  <head>
    <title>Log in</title>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/login.css">
		<link rel="stylesheet" href="assets/css/main.css">
  </head>

  <body class="background-img">

		<!-- It will include the header page and it contains the navigation bar, user profile, user name & email address.  -->
    <?php require_once('header.php'); ?>

    <div class="container-fluid" id="ablum-body">
      <h1 class="text-center text-danger"> Albums </h1>
      <div class="row" id="album">
        <?php
				
				// Checking the user albums are available or not.
				if (isset($_SESSION['userData']['albums'])) {

					// Find the totla number of albums in the user profiel.
          $albumLenght = sizeof($_SESSION['userData']['albums']);

          for($i=0; $i<$albumLenght; $i++){

						// Count the totla number of photos in the particular album.
            $count = $_SESSION['userData']['albums'][$i]['count'];
        ?>
					<!-- To show the full screen slider of the user album photo. -->
          <div id="myNav<?php echo $i; ?>" class="overlay">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav(<?php echo $i; ?>)">&times;</a>
            <div class="" id="slider<?php echo $i; ?>">
              <?php
                for($j=0; $j<$count; $j++){
              ?>
              <div class="slide<?php echo $i; echo $j; ?> overlay-content" id="slide-number<?php echo $i; echo $j; ?>">
								<!-- It  will get the album source from the session. -->
								<img src="<?php echo $_SESSION['userData']['albums'][$i]['photos'][$j]['images']['1']['source']; ?>" class="img-responsive center-img">
              </div>
              <?php
                }
              ?>
            </div>
          </div>

					<!-- To show the user albums in thumbnail using bootstrap classes. -->
          <div class="col-md-3 animated fadeInDown">
            <div class="thumbnail">
              <div id="thumbnail-responsive">
                <div class="circular-portrait">
                  <img src="<?php echo $_SESSION['userData']['albums'][$i]['photos']['0']['images']['0']['source']; ?>" class="" onclick="openNav(<?php echo $i; ?>, <?php echo $count; ?>)">
                </div>
              </div>
              <div class="caption">
								<!-- It will get the name og the album and total number of photos in the album. -->
                <p class="text-center text-uppercase text-success"> <?php echo $_SESSION['userData']['albums'][$i]['name']; ?></p>
                <p class="text-center text-uppercase text-warning"> <?php echo $_SESSION['userData']['albums'][$i]['count']; ?> items</p>

								<div class="form-group">

									<input type ="button" class="btn btn-primary btn-block" id="btn(<?php echo $i; ?>)" onclick="converToZip(<?php echo $i; ?>, <?php echo $count; ?>)" value="Create Zip" data-target="#myModal<?php echo $i; ?>" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                </div>
                <div class="form-group">
                  <a href="upload-album.php?singleID=<?php echo $i; ?>" class="btn btn-danger btn-block " id="btn"> Move to Drive </a>
                </div>
              </div>
            </div>
          </div>

					<!-- Converting user album into zip file using the bootstrap modal. -->
					<div class="modal fade bd-example-modal-lg" id="myModal<?php echo $i;?>" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" onclick="resetTime()" id="closeBtn<?php echo $i; ?>">&times;</button>
									<h4 class="modal-title"> Cover to zip</h4>
								</div>

								<div class="modal-body">
									<div class="progress">
										<div id="dynamic<?php echo $i; ?>" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
											<span id="current-progress"></span>
										</div>
									</div>
									<div id="zip<?php echo $i; ?>"> </div>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
			}
			else{ ?>
				<div class="col-md-8 col-md-offset-2 animated fadeInDown" id="empty-album">
					<h4 class="text-center text-info"> There are no albums available in your facebook account. </h4>
				</div>
			<?php }
				?>
			</div>
		</div>


      <script src="assets/js/jquery.js"></script>
      <script src="assets/bootstrap/js/bootstrap.js"></script>
      <script src="assets/js/slider.js"></script>
			<script src="assets/js/download-zip.js"></script>
  </body>
<html>
