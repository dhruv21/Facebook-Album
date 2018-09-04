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
        $accessToken = $_SESSION['fb_access_token'];
    }
?>
<html>
    <head>
        <title>Log in</title>
        
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/animation.css">
        <link rel="stylesheet" href="assets/css/login.css">
        <link rel="stylesheet" href="assets/css/main.css">
        
        <style>
            #img-1{
                height: 40%;
                width:40%;
                display: block;
		    margin-left: auto;
		    margin-right: auto;
            }
        </style>
    </head>

  <body class="background-img">

    <!-- It will include the header page and it contains the navigation bar, user profile, user name & email address.  -->
    <?php require_once('header.php'); ?>

    <div class="container-fluid" id="ablum-body">
        <h1 class="text-center text-danger"> Albums </h1>
            <div class="row" id="album">
            
            <?php
                
            // Checking the user albums are available or not.
            if(isset($_REQUEST['album']) && isset($_REQUEST['index'])) {
                $index = $_REQUEST['index']-10;
                
				$response = $FB->get("/me?fields=albums.limit(10).offset($index){count,name,id,photos{images,id}}", $accessToken);
				$userData = $response->getGraphNode()->asArray();
                
                // Find the totla number of albums in the user profiel.
				$albumLength = sizeof($userData['albums']);

                for($i=0; $i<$albumLength; $i++){

				// Count the totla number of photos in the particular album.
                $count = $userData['albums'][$i]['count'];
                
                
            ?>
            <!-- To show the full screen slider of the user album photo. -->
            <div id="myNav<?php echo $i; ?>" class="overlay">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav(<?php echo $i; ?>)" data-toggle="modal" data-target="#myModal">&times;</a>
                
                <div class="" id="slider<?php echo $i; ?>">
                
                    <?php
                    for($j=0; $j<$count; $j++){
                        if(($j%100) == 0){
                            $temp2 = ($j/100)*100;
                            $response2 = $FB->get("/me?fields=albums{count,id,photos.limit(100).offset($temp2){images,id},name}", $accessToken);
                            $userData2 = $response2->getGraphNode()->asArray();
                        }    
                    ?>
                    <div class="slide<?php echo $i; echo $j; ?> overlay-content" id="slide-number<?php echo $i; echo $j; ?>">

                        <h3 style="margin-top:100px;" class="text-warning"> <?php echo "Image Number : ". ($j+1); ?></h3>

                        <!-- It  will get the album source from the session. -->
                        <img src="<?php echo $userData2['albums'][$i]['photos'][($j % 100)]['images']['0']['source']; ?>" class="img-responsive center-img">
                    </div> <?php } ?>
                </div>
            </div>

            <!-- To show the user albums in thumbnail using bootstrap classes. -->
            <div class="col-md-3 animated fadeInDown">
                <div class="thumbnail">
                    <div id="thumbnail-responsive">
                        <div class="circular-portrait">
                           <?php if(!isset($userData['albums'][$i]['photos']['0']['images']['0']['source'])){ 
                           
                           
						$userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id']."_0IMG";
						$rootPath = 'Temp/'.$userFolderName."/";
						if (!is_dir($rootPath)) {
						      mkdir($rootPath, 0777);
						     }
			  
			  
			  
                           ?>
                        <img src="assets/images/user-silhouette.png" class="">
                           <?php
                            
                    }
                           else{  ?>
                            <img src="<?php echo $userData['albums'][$i]['photos']['0']['images']['0']['source']; ?>" class="" onclick="openNav(<?php echo $i; ?>, <?php echo $count; ?>)">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="caption">
                    <!-- It will get the name og the album and total number of photos in the album. -->
                      <p class="text-center text-uppercase text-success"> 
                        <?php echo $userData['albums'][$i]['name']; ?>
                        </p>
                        <p class="text-center text-uppercase text-warning"> 
                        <?php echo $userData['albums'][$i]['count']; 
                        
                        	if($userData['albums'][$i]['count']>350){
			$userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id']."_350IMG";
			$rootPath = 'Temp/'.$userFolderName."/";
			if (!is_dir($rootPath)) {
			      mkdir($rootPath, 0777);
			  }
		}
                        ?> items
                        </p>
                        
                       <?php if(isset($userData['albums'][$i]['photos']['0']['images']['0']['source'])){ ?>
                        

                        <div class="form-group">

                            <input type ="button" class="btn btn-primary btn-block" id="btn<?php echo $i; ?>" onclick="converToZip(<?php echo $i; ?>, <?php echo $count; ?>, <?php echo $index; ?>)" value="Create Zip" data-target="#myModal<?php echo $i; ?>" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                        </div>
                        
                        <div class="form-group">
                        <?php 
                        if (!isset($_SESSION['g_access_token'])) { ?>
		<a href="upload-album.php?singleID=<?php echo $i; ?>" class="btn btn-danger btn-block " id="btn"> Move to Drive </a>
	<?php }else{ ?>
                        	
				<input type ="button" class="btn btn-danger btn-block" id="drive<?php echo $i; ?>" onclick="singleAlbum(<?php echo $i; ?>, <?php echo $count; ?>, <?php echo $index; ?>);" value="Move to Drive" data-target="#myModal11" data-toggle="modal" data-backdrop="static" data-keyboard="false">
				<?php } ?>
                            
                            </div>
                        <?php
                            
                    }
                    else{
                        ?>
                        <div class="form-group">

                            <input type ="button" class="btn btn-primary btn-block disabled" value="Create Zip">
                        </div>
                        
                        <div class="form-group">
                           <input type ="button" class="btn btn-danger btn-block disabled" value="Move to Drive">
                        </div>
                        <?php
                    } ?>
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

                            <?php
                                $userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id']."_".$userData['albums'][$i]['name'];

                                $zipPath = 'zipAlbum/'.$userFolderName.'.zip';
                            ?>
                            <a href="<?php echo $zipPath; ?>" class="btn btn-info" id="download-btn<?php echo $i;?>">Download</a>

                            <div id="zip<?php echo $i; ?>"> </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <?php } } else{ 
                $response = $FB->get("/me?fields=albums.limit(10).offset(0){count,name,id,photos{images,id}}", $accessToken);
                $userData = $response->getGraphNode()->asArray();

                if(sizeof($userData)>1){
                    $albumLength = sizeof($userData['albums']);
                    header('Location: login.php?album='.$albumLength.'&index=10');
                }
                else{ ?>
                
				<div class="col-md-8 col-md-offset-2 animated fadeInDown" id="empty-album">
				    <h4 class="text-center text-info"> 
				    There are no albums available in your facebook account. 
				    </h4>
                </div>
                <?php  } } ?>
			</div>
			
			
            <div class="row">
                <div class="col-xs-12" align="center">
                    <ul class="pagination">
                    
                    <?php
		              $response = $FB->get("/me?fields=albums.limit(10).offset(0){count,name,id}", $accessToken);
		              $userData = $response->getGraphNode()->asArray();

		              x($userData, 1, $FB, $accessToken, 0, 1);

                      function a($index, $FB, $accessToken, $a_i, $count){
			             $response3 = $FB->get("/me?fields=albums.limit(10).offset($index){count,name,id}", $accessToken);
			             $userData3 = $response3->getGraphNode()->asArray();
			             $index = (($index/10)+1);

			             x($userData3, $index, $FB, $accessToken, $a_i, $count);
		              }

		              function x($value, $index, $FB, $accessToken, $a_i, $count){
                          if((sizeof($value)) > 1){
                            $a_i = sizeof($value['albums']);
                            $index = $index*10;
				
				if($index>25){
					$userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id']."_DIR";
  					$rootPath = 'Temp/'.$userFolderName."/";
					if (!is_dir($rootPath)) {
					      mkdir($rootPath, 0777);
					  }
				}

                            ?> <li>
                                <a href="?album=<?php echo $a_i;?>&index=<?php echo $index; ?>"> 
                                <?php echo $count; ?> 
                                </a> 
                            </li>
                            <?php
                            $count++;
                            a($index, $FB, $accessToken, $a_i, $count);

                            }
                        }
                        ?> 
                </ul> 
            </div> 
        </div>
    </div>

      
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Alert</h4>
                </div>
                <div class="modal-body">
                    <p>Page is loading once again for your batter interface.</p>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade bd-example-modal-lg" id="myModal11" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" onclick="x()"  id="closeBtn">&times;</button>
          <h4 class="modal-title"> Upload Albums to Google Drive</h4>
        </div>

        <div class="modal-body">
        
        	<div id ="img-1">
           <img src="assets/images/loding.gif"  class="img-responsive">
       </div>
          

			<div class="" id="error">
						<h4 class="text-center text-info"> You have to select album. </h4>
          </div>		

          <div id="zip">  </div>
          
          <h4 id="download-btn"> Aulbums Uploaded...</h4>

        </div>
      </div>
    </div>
  </div>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/bootstrap/js/bootstrap.js"></script>
    <script src="assets/js/slider.js"></script>
    <script src="assets/js/download-zip.js"></script>
    <script src="assets/js/upload-album.js"></script>
    
    
    <script>
    	function x(){
    	location.reload();
    	}
    	
    </script>
    
  </body>
<html>
