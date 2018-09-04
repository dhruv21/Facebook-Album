<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 28-06-2018
	Programming Language: php
	Purpose: User can download albums in zip and select multipale albums as well as all the albums.
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
        $rootFolder = "facebook_".$_SESSION['userData']['first_name']."_albums";
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/animation.css">
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/css/download-album.css">
    </head>


    <body class="background-img">

        <!-- It will include the header page and it contains the navigation bar, user profile, user name & email address.  -->
        <?php require_once('header.php'); ?>

        <div class="container-fluid animated fadeInDown" id="body-blog">
            <div class="row" id="download-album">
                <div class="col-md-8 col-md-offset-2">
                    <?php
                        // It will get the user albums. 
                        $response = $FB->get("/me?fields=albums.limit(10).offset(0){count,name,id}", $accessToken);
                        $userData = $response->getGraphNode()->asArray();

                        if(sizeof($userData)>1){ 
                    ?>

                    <div class="table-responsive" id="table1">
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
                                $temp1 = 0;
                                
                                // To check total number of albums in the user account.
                                function getAlbums($index, $FB, $accessToken, $albumSize, $temp2, $temp3){
                                    $response3 = $FB->get("/me?fields=albums.limit(10).offset($index){count,name,id}", $accessToken);
                                    $userData3 = $response3->getGraphNode()->asArray();
                                    $index = (($index/10)+1);

                                    checkAlbums($userData3, $index, $FB, $accessToken, $albumSize, $temp2, $temp3);
                                }
                                
                                // Get user album information.
                                function checkAlbums($value, $index, $FB, $accessToken, $albumSize, $temp2, $temp3){
                                    if((sizeof($value)) > 1){
                                        $albumSize = sizeof($value['albums']);

                                        for($i=0; $i<$albumSize; $i++){
                            ?>
                                <tr>
                                    <td>
                                    <?php 
                                        if(!$value['albums'][$i]['count'] == 0){
                                    ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="check_list" value="<?php echo $j ."_". $value['albums'][$i]['count']; ?>"> </label>
                                    </div>
                                    <?php 
                                        } 
                                    ?>
                                    </td>

                                    <td class="text-justify"> <img src="assets/images/folder.png">  
                                    <?php echo $value['albums'][$i]['name']; ?> 
                                    </td>

                                    <td> 
                                        <small class="badge"> Items <?php echo $value['albums'][$i]['count']; ?> </small> 
                                    </td>
                                </tr>
                                        <?php
                                            $GLOBALS['temp2'] = $temp2++;
                                            $GLOBALS['temp1'] = $GLOBALS['temp1'] + $value['albums'][$i]['count'];
                                        }
                                        $index = $index*10;

                                        getAlbums($index, $FB, $accessToken, $albumSize, $temp2, $temp3);

                                        }
                                    }
                                    checkAlbums($userData, 1, $FB, $accessToken, 0, 0, 0);
                                    ?>

                            </tbody>
                        </table>

                        <table class="table">
                            <tr>
                                <th> 
                                    <input type="button" name="button1" id="button1" value="Create Selected Album Zip" class="btn btn-info btn-block" onclick="selectedAlbums()" data-target="#myModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"/>
                                </th>


                                <th> 
                                    <input type="button" name="button2" value="Create All Album Zip" class="btn btn-primary btn-block" onclick="allAlbums(<?php echo $j; ?>, <?php echo $t1;?>)" data-target="#myModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"/> 
                                </th>
                            </tr>
                        </table>
                    </div> 
                    <?php
                        }
                        else{ 
                    ?>
                    <div class="" id="empty-album">
                        <h4 class="text-center text-info"> There are no albums available in your facebook account. </h4>
                    </div>
                    <?php 
                        } 
                    ?>
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
