<html>
	<head>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/animation.css">
        <link rel="stylesheet" href="assets/css/main.css">
	</head>
	
	<body>
	
    <?php
        require_once "g-config.php";
    	require_once "f-config.php";
        
        if (!isset($_SESSION['fb_access_token'])) {
		  header('Location: index.php');
		  exit();
	   }
	   else{
           $accessToken = $_SESSION['fb_access_token'];
	   }

        if (!isset($_SESSION['g_access_token'])) {
            header('Location: google-login.php');
            exit();
        }

        if(isset($_REQUEST['indexValue'])){

            $indexValue = explode(",",$_REQUEST['indexValue']);

            $gClient->setAccessToken($_SESSION['g_access_token']);
            $drive = new Google_Service_Drive($gClient);


            $offset = $indexValue[0];

            unset($indexValue[0]);
            $arryString = implode(",",(array_filter($indexValue)));

            $response = $FB->get("/me?fields=albums.limit(1).offset($offset){count,name}", $accessToken);
            $userData = $response->getGraphNode()->asArray();

            $id = $userData['albums']['0']['id'];

            $countPhoto = (int)$userData['albums']['0']['count'];

            $tempVal=0;

            for($i=100;$i<$countPhoto;$i++){
                if(($i%100)==0){
                    $tempVal++;
                }
            }

            // Create root directory in google drive and set id into session.
            if (!isset($_SESSION['rootID'])) {
                $rootFolder = "facebook_".$_SESSION['userData']['first_name']."_albums";

                $fileMetadata = new Google_Service_Drive_DriveFile(array(
                    'name' => $rootFolder,
                    'mimeType' => 'application/vnd.google-apps.folder'));
                $rootFile = $drive->files->create($fileMetadata, array('fields' => 'id'));

                $_SESSION['rootID'] = $rootFile->id;
            }

            $childFolder = $userData['albums']['0']['name'];
            
            // Create childe directory in root directory and set id into session.
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $childFolder,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => array($_SESSION['rootID'])
            ));


            $childFile = $drive->files->create($fileMetadata, array('fields' => 'id'));
            $_SESSION['childID'] = $childFile->id; 

            $response = $FB->get("/$id/photos?fields=images&limit=100", $accessToken)->getgraphEdge();

            $userData = $response->asArray();

            for($i=0; $i<sizeof($userData); $i++){
                $fileMetadata = new Google_Service_Drive_DriveFile(array(
                    'name' => $childFolder.'-'.($i+1).'.jpg',
                    'parents' => array($_SESSION['childID'])
                ));
                $imagePath = $userData[$i]['images']['0']['source'];

                $content = file_get_contents($imagePath);
                
                // save image into child folder.
                $file = $drive->files->create($fileMetadata, array(
                    'data' => $content,
                    'mimeType' => 'image/jpeg',
                    'uploadType' => 'multipart',
                    'fields' => 'id')); 
            }    

            if($countPhoto > 100){
                header("location:upload-data.php?stringData=$arryString&tempVal=$tempVal&offset=$offset");
                exit();
            }
            else{
                if(empty($indexValue)){
                    unset($_SESSION['rootID']);
                    unset($_SESSION['childID']);
                    ?>
                    <script>

                    $('#img-1').hide();
                    $("#closeBtn").show();
                    $("#download-btn").show();

                    </script>
                    <?php

                }
                else{
                    unset($_SESSION['childID']);
                    header("location:upload-data.php?indexValue=$arryString");
                }
            } 
        }


        if((isset($_REQUEST['stringData'])) && (isset($_REQUEST['tempVal'])) && (isset($_REQUEST['offset']))){

            $gClient->setAccessToken($_SESSION['g_access_token']);
            $drive = new Google_Service_Drive($gClient);

            $tempVal = (int)$_REQUEST['tempVal'];
            $offset = (int)$_REQUEST['offset'];
            $arryString = $_REQUEST['stringData'];

            if($tempVal != 0){


                $response = $FB->get("/me?fields=albums.limit(1).offset($offset){count,name}", $accessToken);
                $userData = $response->getGraphNode()->asArray();

                $id = $userData['albums']['0']['id'];

                $countPhoto = (int)$userData['albums']['0']['count'];

                $childFolder = $userData['albums']['0']['name'];

                $response = $FB->get("/$id/photos?fields=images&limit=100", $accessToken)->getgraphEdge();

                for($i=0; $i<$tempVal; $i++){
                    $response = $FB->next($response);
                    $userData = $response->asArray();
                }


                $temp = $tempVal*100;
                for($i=0; $i<sizeof($userData); $i++){
                    $temp++;
                    $fileMetadata = new Google_Service_Drive_DriveFile(array(
                        'name' => $childFolder.'-'.$temp.'.jpg',
                        'parents' => array($_SESSION['childID'])
                    ));
                    $imagePath = $userData[$i]['images']['0']['source'];

                    $content = file_get_contents($imagePath);
                    
                    // save image into child folder.
                    $file = $drive->files->create($fileMetadata, array(
                        'data' => $content,
                        'mimeType' => 'image/jpeg',
                        'uploadType' => 'multipart',
                        'fields' => 'id'));  
                } 

                $tempVal = ((int)$tempVal)-1;
                header("location:upload-data.php?stringData=$arryString&tempVal=$tempVal&offset=$offset");
                exit();
            }
            else{
                if(empty($arryString)){
                    unset($_SESSION['rootID']);
                    unset($_SESSION['childID']);
                    ?>
                    <script>

                    $('#img-1').hide();
                    $("#closeBtn").show();
                    $("#download-btn").show();
        
                    </script>
                    <?php

                }
                else{
                    unset($_SESSION['childID']);
                    header("location:upload-data.php?indexValue=$arryString");
                }


            } 
        } 

        ?>


    <script src="assets/js/jquery.js"></script>
    <script src="assets/bootstrap/js/bootstrap.js"></script>
    <script src="assets/js/upload-album.js"></script>
    </body>
</html>

