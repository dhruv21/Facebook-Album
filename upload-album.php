<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 01-07-2018
	Programming Language: php
	Purpose: It is used to upload the user albums to the google drive account. user can upload single album, multipale aulbums, and as well as all the aulbums.
-->

<?php
	require_once "g-config.php";

	if (!isset($_SESSION['fb_access_token'])) {
		header('Location: index.php');
		exit();
	}
	else{
		$rootFolder = "facebook_".$_SESSION['userData']['first_name']."_albums";
	}

	if (!isset($_SESSION['g_access_token'])) {
		header('Location: google-login.php');
		exit();
	}

?>

<html>
  <head>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/main.css">
  </head>

  <body class="background-img">
    <?php require_once('header.php');


		if(isset($_REQUEST['singleID'])){

			$gClient->setAccessToken($_SESSION['g_access_token']);
			$drive = new Google_Service_Drive($gClient);
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
						'name' => $rootFolder,
						'mimeType' => 'application/vnd.google-apps.folder'));

			$rootFile = $drive->files->create($fileMetadata, array('fields' => 'id'));

			$i = $_REQUEST['singleID'];
			$childFolder = $_SESSION['userData']['albums'][$i]['name'];



			$fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $childFolder,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($rootFile->id)
    ));
    $childFile = $drive->files->create($fileMetadata, array('fields' => 'id'));
		$album_folder = $childFile->id;



		$count = $_SESSION['userData']['albums'][$i]['count'];

		for($j = 0; $j < $count; $j++){
			$imagePath = $_SESSION['userData']['albums'][$i]['photos'][$j]['images']['1']['source'];
			$content = file_get_contents($imagePath);

		$folderId = $album_folder;

		$fileMetadata = new Google_Service_Drive_DriveFile(array(
		    'name' => 'photo'.$j.'.jpg',
		    'parents' => array($folderId)
		));

		$file = $drive->files->create($fileMetadata, array(
		    'data' => $content,
		    'mimeType' => 'image/jpeg',
		    'uploadType' => 'multipart',
		    'fields' => 'id'));
		}



		header('Location: login.php');
	}
	else if(isset($_POST['submit1'])){

		if(!empty($_POST['check_list'])) {
			$rootFolder = "facebook_".$_SESSION['userData']['first_name']."_albums";

			$gClient->setAccessToken($_SESSION['g_access_token']);
			$drive = new Google_Service_Drive($gClient);
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
						'name' => $rootFolder,
						'mimeType' => 'application/vnd.google-apps.folder'));
			$rootFile = $drive->files->create($fileMetadata, array('fields' => 'id'));

			    foreach($_POST['check_list'] as $check) {
			            $childFolder = $_SESSION['userData']['albums'][$check]['name'];

									$fileMetadata = new Google_Service_Drive_DriveFile(array(
						        'name' => $childFolder,
						        'mimeType' => 'application/vnd.google-apps.folder',
						        'parents' => array($rootFile->id)
						    ));
						    $childFile = $drive->files->create($fileMetadata, array('fields' => 'id'));
								$album_folder = $childFile->id;


								$count = $_SESSION['userData']['albums'][$check]['count'];
								for($j = 0; $j < $count; $j++){

								$folderId = $album_folder;
								$fileMetadata = new Google_Service_Drive_DriveFile(array(
										'name' => 'photo'.$j.'.jpg',
										'parents' => array($folderId)
								));

								$imagePath = $_SESSION['userData']['albums'][$check]['photos'][$j]['images']['1']['source'];
								$content = file_get_contents($imagePath);
								$file = $drive->files->create($fileMetadata, array(
										'data' => $content,
										'mimeType' => 'image/jpeg',
										'uploadType' => 'multipart',
										'fields' => 'id'));
								}


			    }


				}

	else{
		?> <div class="container-fluid" style= "margin-top:100px; background-color:#FFFFFF;">
			<h4 class="text-center text-danger"> You have not chacked any Album. </h4>
		</div> <?php
	}
	}
	else if(isset($_POST['submit2'])){

			$rootFolder = "facebook_".$_SESSION['userData']['first_name']."_albums";

			$gClient->setAccessToken($_SESSION['g_access_token']);
			$drive = new Google_Service_Drive($gClient);
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
						'name' => $rootFolder,
						'mimeType' => 'application/vnd.google-apps.folder'));
			$rootFile = $drive->files->create($fileMetadata, array('fields' => 'id'));
			$albumLenght = sizeof($_SESSION['userData']['albums']);
			for($i=0;$i<$albumLenght;$i++) {
			            $childFolder = $_SESSION['userData']['albums'][$i]['name'];

									$fileMetadata = new Google_Service_Drive_DriveFile(array(
						        'name' => $childFolder,
						        'mimeType' => 'application/vnd.google-apps.folder',
						        'parents' => array($rootFile->id)
						    ));
						    $childFile = $drive->files->create($fileMetadata, array('fields' => 'id'));
								$album_folder = $childFile->id;


								$count = $_SESSION['userData']['albums'][$i]['count'];
								for($j = 0; $j < $count; $j++){
								$folderId = $album_folder;
								$fileMetadata = new Google_Service_Drive_DriveFile(array(
										'name' => 'photo'.$j.'jpg',
										'parents' => array($folderId)
								));



								$imagePath = $_SESSION['userData']['albums'][$i]['photos'][$j]['images']['1']['source'];
								$content = file_get_contents($imagePath);
								$file = $drive->files->create($fileMetadata, array(
										'data' => $content,
										'mimeType' => 'image/jpeg',
										'uploadType' => 'multipart',
										'fields' => 'id'));
								}


			    }


	}
	else{
		?>

		<?php
	}
		?>

		<div class="container-fluid" style= "margin-top:100px; background-color:#FFFFFF;">
		<div class="row" id="download-album">
			<div class="col-md-8 col-md-offset-2">
				<div class="table-responsive" style="margin-top:100px;">
					<?php if (isset($_SESSION['userData']['albums'])) { ?>
					<form method="post">
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
						$albumLenght = sizeof($_SESSION['userData']['albums']);

						for($i=0; $i<$albumLenght; $i++){
							?>
								<tr>
									<td>
										<div class="checkbox">
												<label><input type="checkbox" name="check_list[]" value="<?php echo $i; ?>"> </label>
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
								<th> <input type="submit" name="submit1" value="Upload Selected Album" class="btn btn-info btn-block"/></th>

								<th><input type="submit" name="submit2" value="Upload All Album" class="btn btn-primary btn-block"/> </th>
							</tr>
						<tbody>
					</table>
				</form>
				<?php
				}
			else{ ?>
				<div class="" id="empty-album">
					<h4 class="text-center text-info"> There are no albums available in your facebook account. </h4>
				</div>
			<?php } ?>
				</div>
			</div>
		</div>
	</div>
		<script src="assets/js/jquery.js"></script>
		<script src="assets/bootstrap/js/bootstrap.js"></script>
		<script src="assets/js/slider.js"></script>
  </body>
</html>
