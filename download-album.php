<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 28-06-2018
	Programming Language: php
	Purpose: User can download albums in zip and select multipale albums as well as all the albums.
-->


<?php
	require_once "g-config.php";

	// To check the user is login with facebook or not.
	if (!isset($_SESSION['fb_access_token'])) {
		header('Location: index.php');
		exit();
	}
	else{
		$rootFolder = "facebook_".$_SESSION['userData']['first_name']."_albums";
	}
?>

<html>
  <head>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/main.css">
  </head>


  <body class="background-img">

		<!-- It will include the header page and it contains the navigation bar, user profile, user name & email address.  -->
    <?php require_once('header.php'); ?>

		<div class="container-fluid animated fadeInDown" style= "margin-top:100px; background-color:#FFFFFF;">
			<div class="row" id="download-album">
      	<div class="col-md-8 col-md-offset-2">

					<?php // Checking the user albums are available or not.
					if (isset($_SESSION['userData']['albums'])) { ?>
        	<div class="table-responsive" style="margin-top:100px;">
          	<table class="table table-hover">
            	<thead>
              	<tr class="success">
	                <th></th>
	                <th class="text-justify">Album Name</th>
									<th> </th>
              	</tr>
            	</thead>
            <tbody>

          <?php

						// show the all album name and totla number of photos.
            $albumLenght = sizeof($_SESSION['userData']['albums']);

            for($i=0; $i<$albumLenght; $i++){
              ?>
                <tr>
                  <td>
                    <div class="checkbox">
                        <label><input type="checkbox" name="check_list" value="<?php echo $i; ?>"> </label>
                    </div>
                  </td>

                  <td class="text-justify"> <img src="assets/images/folder.png">  <?php echo $_SESSION['userData']['albums'][$i]['name']; ?> </td>
									<td> <small class="badge"> Items <?php echo $_SESSION['userData']['albums'][$i]['count']; ?> </small>  </td>
                </tr>
              <?php
            }
          ?>

            </tbody>
          </table>


					<table class="table">
              <tr>
                <th> <input type="button" name="button1" id="button1" value="Create Selected Album Zip" class="btn btn-info btn-block" onclick="selectedAlbums()" data-target="#myModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"/></th>

								<th> <input type="button" name="button2" value="Create All Album Zip" class="btn btn-primary btn-block" onclick="allAlbums(<?php echo $albumLenght; ?>)" data-target="#myModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"/> </th>
              </tr>
            <tbody>
					</table>
        </div> <?php
				}
			else{ ?>
				<div class="" id="empty-album">
					<h4 class="text-center text-info"> There are no albums available in your facebook account. </h4>
				</div>
			<?php } ?>
      </div>
    </div>
	</div>


	<!-- Converting user album into zip file using the bootstrap modal. -->
  <div class="modal fade bd-example-modal-lg" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" onclick="resetTime()" id="closeBtn">&times;</button>
          <h4 class="modal-title"> Cover to zip</h4>
        </div>

        <div class="modal-body">
          <div class="progress" id="progress">
            <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
              <span id="current-progress"></span>
            </div>
          </div>

					<div class="" id="error">
						<h4 class="text-center text-info"> You have to select album. </h4>
          </div>

          <div id="zip"> </div>
					<?php

						$userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id'];
						$zipPath = 'zipAlbum/'.$userFolderName.'.zip';
					?>
				  <a href="<?php echo $zipPath; ?>" class="btn btn-info" id="download-btn">Download</a>

        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/jquery.js"></script>
  <script src="assets/bootstrap/js/bootstrap.js"></script>
	<script src="assets/js/download-zip.js"></script>
  </body>
</html>
