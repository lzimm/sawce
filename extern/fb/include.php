<?php

	include('client/facebook.php');
	include('../../config/config.php');
		
	function __autoload($class_name) {
		$class_name = str_replace('_', '/', $class_name);
		
		if (file_exists($GLOBALS['cfg']['basedir'] . 'libs/' . $class_name . '.php')) {
			require_once($GLOBALS['cfg']['basedir'] . 'libs/' . $class_name . '.php');
		} else {
			require_once($class_name . '.php');
		}
	}

	function microtime_float() {
    	list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
	}
	
	$appapikey = 'ed689a306d6105f64612969fa5dbaa2c';
	$appsecret = 'bbfa930071da6b1095719ad729d4be62';
	
	$facebook = new Facebook($appapikey, $appsecret);
	$facebook->require_frame();
	$fb_user = $facebook->require_login();
	
	$user = null;
	if ($fb_user) {
		$user = User::find_fb($fb_user);
	}

	$appcallbackurl = 'http://fb.sawce.net/';

	try {
		if (!$facebook->api_client->users_isAppAdded()) {
	    	$facebook->redirect($facebook->get_add_url());
	  	}
	} catch (Exception $ex) {
	  	$facebook->set_user(null, null);
	  	$facebook->redirect($appcallbackurl);
	}

	session_id(substr($facebook->api_client->session_key, 0, 30));
	session_start();

	if (!isset($_SESSION['errors'])) {
		$_SESSION['errors'] = array();
	}

	if (!isset($_SESSION['messages'])) {
		$_SESSION['messages'] = array();
	}
	
?>