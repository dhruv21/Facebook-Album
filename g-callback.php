<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 30-06-2018
	Programming Language: php
	Purpose: It is use to authenticate the user from google account and set the access token.
-->

<?php
	require_once "g-config.php";

	if (isset($_SESSION['g_access_token']))
		$gClient->setAccessToken($_SESSION['g_access_token']);
	else if (isset($_GET['code'])) {
		$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['g_access_token'] = $token;
	} else {
		echo "seesion";
		header('Location: login.php');
		exit();
	}

	header('Location: login.php');
	exit();
?>
