<?php

	global $req;

	if (Util::check_authed()) {
		shift_page('my', 'home');
	} else {
		global $u_email;
		global $u_pass;
		global $u_submit;
	
		$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
		$u_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
		$u_submit	= new SubmitValidator('login', array('label' => 'Login'));
	
		if ($_POST) {
			try {
				if ($user = User::auth($u_email->get(), $u_pass->get())) {
					Util::auth_session($user);
					
					Util::user_message(MSG_USER_LOGIN);
					
					if (isset($req['pg'])) {
						shift_page($req['pg']);
					} else {
						shift_page('my', 'home');
					}
				} else {
					Util::user_error(ERR_LOGIN_FAILED);
				}
			} catch (Exception $e) {
				Util::catch_exception($e);
			}
		}
	}
	
?>
