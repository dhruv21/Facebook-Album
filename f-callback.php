<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 27-06-2018
	Programming Language: php
	Purpose: To set the access token and user information for other pages. Like checking the user is login or note
          dispaly the albums, user name etc...
-->

<?php
  require_once "f-config.php";

  try{
    // geting the access token from the facebook.
    $accessToken = $helper->getAccessToken();
  }
  catch(\Facebook\Exceptions\FacebookResponseException $e){
    echo "Response Exeption". $e->getMessage();
    exit();
  }
  catch(\Facebook\Exceptions\FacebookSDKException $e){
    echo "SDK Exeption". $e->getMessage();
    exit();
  }

  if(!$accessToken){
    header("Location:index.php");
    exit();
  }

  // checking the user is authenticate or not.
  $oAuth2Client = $FB->getoAuth2Client();
  if (!$accessToken->isLongLived())
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);


  // get the user facebook data (first name, last name, email, profile photo, album data).
  $response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large),albums{count,name,photos{images}}", $accessToken);
	$userData = $response->getGraphNode()->asArray();

  // set the session for storing the user data.
	$_SESSION['userData'] = $userData;
  // set the session for storing the user access token.
	$_SESSION['fb_access_token'] = (string) $accessToken;

	header('Location: login.php');
?>
