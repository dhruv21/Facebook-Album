<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 30-06-2018
	Programming Language: php
	Purpose: To set the google application basic settings like  client id, client secret and it can be used in other pages to interact with user and goole application.
-->

<?php
    session_start();
    require_once "lib/Google/vendor/autoload.php";

    $gClient = new Google_Client();
    $gClient->setClientId("1021195967962-7tpp70515i54dk1k98eluqvbgs5h8gef.apps.googleusercontent.com");
    $gClient->setClientSecret("dLIVK4ovgEDmqOS0_wDrbLfG");

    $google_redirect_url = "http://localhost/rtcampFacebookPhotoGallery/g-callback.php";
    $gClient = new Google_Client();
    $gClient->setAuthConfigFile('google.json');
    $gClient->setRedirectUri($google_redirect_url);
    $gClient->addScope(Google_Service_Drive::DRIVE);

?>
