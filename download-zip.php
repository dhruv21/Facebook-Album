<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 28-06-2018
	Programming Language: php
	Purpose: It is used to conver album into the zip file and zip fill will be download in localhost and user can download it.
-->

<?php
    require_once "f-config.php";

    // To check the user is login with facebook or not.
    if (!isset($_SESSION['fb_access_token'])) {
        header('Location: index.php');
        exit();
    }
    else{
        $accessToken = $_SESSION['fb_access_token'];
    }

    // Function use to create the directory on the local server.
    function createDir($dirPath){
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777);
        }
    }

    // Function user for download the image on local server and it will store on particular folder.
    function checkingImage($path, $url){
        $checkImage = $path."."."jpeg";
        if (!file_exists($checkImage)) {
            $img = $path."."."jpeg";
            file_put_contents($img, file_get_contents($url));
        }
    }

    // Function use for convert created folder in to zip file.
    function createZip($realPath, $zipPath){
        if (is_dir($realPath)) {
            $rootPath = realpath($realPath);
            $zip = new ZipArchive();
            $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file){
                if (!$file->isDir()){
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
    }

    // Function use for deleting the directory from the local server.
    function deleteDirectory($dir) {
        if (is_dir($dir)) {
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') {
                    continue;
                }
                system('rm -rf ' . escapeshellarg($dir . DIRECTORY_SEPARATOR . $item), $retval);
            }
            rmdir($dir);
        }
    }

    // It will download single album and convert single album into zip file.
    if(isset($_REQUEST['id'])&&isset($_REQUEST['index'])){

        $index= $_GET['index'];
        $id = $_REQUEST['id'];

        $response = $FB->get("/me?fields=albums.limit(10).offset($index){count,name,id,photos{images,id}}", $accessToken);
        $userData = $response->getGraphNode()->asArray();

        $userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id'];
        $rootPath = 'userAlbum/'.$userFolderName."/";

        // Create username directory.
        createDir($rootPath);

        $userAlbumName = $userData['albums'][$_REQUEST['id']]['name'];
        $childPath = 'userAlbum/'.$userFolderName."/".$userAlbumName."/";

        // Create album name directory in username directory.
        createDir($childPath, $userAlbumName);

        $count = $userData['albums'][$id]['count'];

        for($j=0; $j<$count; $j++){
            if(($j/100) == 0){
                $temp2 = ($j/100)*100;
                $response2 = $FB->get("/me?fields=albums{count,id,photos.limit(100).offset($temp2){images,id},name}", $accessToken);
                $userData2 = $response2->getGraphNode()->asArray();
            }
            $imageName = "FBPhoto".$j;
            $path = $childPath.$imageName;
            $url = $userData2['albums'][$id]['photos'][($j % 100)]['images']['0']['source'];

            // User for download the image on local server and it will store on particular folder.
            checkingImage($path, $url);
        }

        $createZipPath = 'zipAlbum/';
        createDir($createZipPath);

        $realPath = 'userAlbum/'.$userFolderName.'/'.$userAlbumName;
        $zipPath = 'zipAlbum/'.$userFolderName.'_'.$userAlbumName.'.zip';

        // Create album into zip file.
        createZip($realPath, $zipPath);

        deleteDirectory($rootPath);
    }

    // It will download the multipal albums and create the directory on local server.
    if(isset($_REQUEST['selectedID1'])){

        if($_REQUEST['selectedID1'] != null){

            $index = $_REQUEST['selectedID1'];
            $response = $FB->get("/me?fields=albums.limit(1).offset($index){count,name,id,photos{images,id}}", $accessToken);
            $userData = $response->getGraphNode()->asArray();

            $userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id'];
            $rootPath = 'userAlbum/'.$userFolderName."/";

            // Create username directory.
            createDir($rootPath);

            $userAlbumName = $userData['albums']['0']['name'];
            $childPath = 'userAlbum/'.$userFolderName."/".$userAlbumName."/";

            //  Create username directory.
            createDir($childPath, $userAlbumName);

            $count = $userData['albums']['0']['count'];
            for($j=0; $j<$count; $j++){
                if(($j/100) == 0){
                    $temp2 = ($j/100)*100;
                    $response2 = $FB->get("/me?fields=albums.limit(1).offset($index){count,id,photos.limit(100).offset($temp2){images,id},name}", $accessToken);
                    $userData2 = $response2->getGraphNode()->asArray();
                }
                $imageName = "FBPhoto".$j;
                $path = $childPath.$imageName;
                $url = $userData2['albums']['0']['photos'][($j % 100)]['images']['0']['source'];
                checkingImage($path, $url);
            }
        }
    }

    // It will convert download albums into zip file.
    if(isset($_REQUEST['selectedID2'])){

        $userFolderName = $_SESSION['userData']['first_name']."_".$_SESSION['userData']['id'];
        $rootPath = 'userAlbum/'.$userFolderName."/";

        $realPath = 'userAlbum/'.$userFolderName;
        $zipPath = 'zipAlbum/'.$userFolderName.'.zip';

        if(file_exists($zipPath)){
            unlink($zipPath);
        }

        // Create multipal albums into zip file. .
        createZip($realPath, $zipPath);

        // Delte user album directory from local host.
        deleteDirectory($rootPath);
    }
?>
