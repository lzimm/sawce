<?php

	if (Util::check_authed()) {
		shift_page('my', 'home');
	} else {
		global $u_email;
		global $u_submit;
	
		$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
		$u_submit	= new SubmitValidator('login', array('label' => 'Submit'));
	
		if ($_POST) {
			try {
				if (Util::lost_password($u_email->get())) {				
					Util::user_message(MSG_EMAILED_PASSWORD);
				}
			} catch (Exception $e) {
				Util::catch_exception($e);
			}
		}
	}
	
?>
