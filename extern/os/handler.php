<?php
	
	include('../../config.php');

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

	$id 		= $_REQUEST['id'];
	$container 	= $_REQUEST['container']
	
	global $u_email;
	global $u_pass;
	global $u_submit;

	$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
	$u_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
	$u_submit	= new SubmitValidator('req_auth', array('label' => 'Login'));
	
	global $user;
	$user = User::find_os($id, $container);
	
	if (!$user && $_POST) {
		try {
			if ($user = User::auth($u_email->get(), $u_pass->get())) {
				$user->set_os($id, $container);
			} else {
				Util::user_error(ERR_LOGIN_FAILED);
			}
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
	}
	
	if ($user) {
		include('sawce.php');
	} else {
		include('login.php');
	}
	
?>