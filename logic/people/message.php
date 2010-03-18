<?php

	global $id;
	global $user;
	global $subject;
	global $body;
	global $submit;
	
	if (Util::check_authed()) {
		if ($id) {
			$user 		= User::find($id);
			
			$subject	= new TextValidator('subject', array('max_len' => 256));
			$body		= new TextValidator('body', array('max_len' => 500, 'type' => 'text'));
			$submit		= new SubmitValidator('submit', array('label' => 'Send Message'));
			
			if ($_POST) {
				try {
					$user->send_message(Util::as_authed()->_id, $subject->get(), $body->get());
					Util::user_message(MSG_MESSAGE_SENT);
					shift_page('people','profile',$id);
				} catch (Exception $e) {
					Util::catch_exception($e);	
				}
			}
		} else {
			Util::user_error(ERR_NO_USER_SELECTED);
			shift_page('people');		
		}
	} else {
		Util::user_error(ERR_NO_LOGIN);
		require_login();
	}
	
?>