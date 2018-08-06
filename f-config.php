<!--
	Programmer Name: Dhruv Y. Ghadiali.
	Creation Date: 27-06-2018
	Programming Language: php
	Purpose: To set the facebook application basic settings like app id, app secret, version and it can be used in other pages to interact with user and application.
-->

<?php
  session_start();
  require_once "lib/Facebook/autoload.php";


  $FB = new \Facebook\Facebook([
    'app_id' => ' 930969873755166',
    'app_secret' => '91ef88888e7ce5c2f9793e90f65e3cfe',
    'default_graph_version' => 'v3.1'
  ]);

  $helper = $FB->getRedirectLoginHelper();
?>
