<?php
session_start();

require_once "lib/Facebook/autoload.php";

// configur with facebook application.
$fb = new Facebook\Facebook([
  'app_id' => '289233891654714',
  'app_secret' => '20266f565c809f71eecb78f4fcfc2ab9',
  'default_graph_version' => 'v2.12',
]);

$helper = $fb->getCanvasHelper();
$permissions = ['email', 'publish_actions'];

try {

  // check the access token is set or not
	if (isset($_SESSION['facebook_access_token'])) {
	   $accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {

  // When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {

  // When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		$_SESSION['facebook_access_token'] = (string) $accessToken;

      // check the user is aurthanticate or not.
		$oAuth2Client = $fb->getOAuth2Client();

    // Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}

	// validating the access token
	try {
		$request = $fb->get('/me');
	} catch(Facebook\Exceptions\FacebookResponseException $e) {

    // When Graph returns an error
		if ($e->getCode() == 190) {
			unset($_SESSION['facebook_access_token']);
			$helper = $fb->getRedirectLoginHelper();
			$loginUrl = $helper->getLoginUrl('https://apps.facebook.com/APP_NAMESPACE/', $permissions);
			echo "<script>window.top.location.href='".$loginUrl."'</script>";
			exit;
		}
	} catch(Facebook\Exceptions\FacebookSDKException $e) {

    // When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	try {

    // message must come from the user-end
		$data = ['source' => $fb->fileToUpload('logout.jpg'), 'message' => 'my photo'];
		$request = $fb->post('/me/photos', $data);
		$response = $request->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {

    // When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {

    // When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	echo $response['id'];

    // Now you can redirect to another page and use the
  	// access token from $_SESSION['facebook_access_token']
} else {
	$helper = $fb->getRedirectLoginHelper();
	$loginUrl = $helper->getLoginUrl('https://apps.facebook.com/APP_NAMESPACE/', $permissions);
	echo "<script>window.top.location.href='".$loginUrl."'</script>";
}
?>
