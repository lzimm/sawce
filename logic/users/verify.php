<?php

	global $u_email;
	global $u_key;
	global $u_submit;

	$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
	$u_key 	    = new TextValidator('pass', array('max_len' => 64, 'key' => TRUE));
	$u_submit	= new SubmitValidator('verify', array('label' => 'Verify'));

	if ($_POST) {
		try {
			if ($user = User::verify($u_email->get(), $u_key->get())) {
				Util::auth_session($user);
				
				Util::user_message(MSG_USER_VERIFIED);
				
				shift_page('my', 'home');
			} else {
				Util::user_error(ERR_VERIFICATION_FAILED);
			}
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
	}
	
?>
