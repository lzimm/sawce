<?php

	global $req;

	if (Util::check_authed()) {
		shift_page('my', 'home');
	} else {
		global $u_name;
		global $u_pass;
		global $u_pass_chk;
		global $u_email;
		global $u_display;
		global $u_profile;
		global $u_type;
		global $u_check;
		global $u_submit;
        
        global $reg_next;
	
		$u_name 	= new TextValidator('name', array('max_len' => 16, 'note' => FNOTE_USER_NAME));
		$u_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
		$u_pass_chk	= new TextValidator('pass_chk', array('max_len' => 64, 'password' => TRUE));
		$u_email 	= new TextValidator('email', array(), new EmailValidationStrategy());
		$u_display	= new TextValidator('display_name', array('required' => FALSE, 'note' => FNOTE_DISPLAY_NAME));
		$u_profile	= new ProfileValidator('profile', array('required' => FALSE));	
		$u_type 	= new SelectValidator('type', array('max_len' => 12, 'default' => UTYPE_FAN,
													'options' => array(
														array('id' => 'fan', 'name' => 'Fan'),
														array('id' => 'artist', 'name' => 'Artist')
													)));
		$u_check	= new CheckValidator('check', array('lookfor' => 'email'));
		$u_submit	= new SubmitValidator('reqister', array('label' => 'Register'));
	
		if ($_POST) {
			try {
				$u_check->get();
				
				if ($u_pass->get() != $u_pass_chk->get()) {
					$u_pass->invalidate();
					$u_pass_chk->invalidate();
					Util::user_error(ERR_PASSWORD_MISMATCH);
				}
				
				if ($u_type->get() == UTYPE_ARTIST) {
					$user = Artist::create($u_name->get(), $u_email->get(), $u_pass->get(), $u_profile->get(), $u_display->get());	
                    
                    Util::step_message(array(STPMSG_ARTIST_CREATE, 
                        array('Learn how it works' => build_link('about','spread'), 
                                'Upload your Music' => build_link('my','albums'))));
				} else {
					$user = User::create($u_name->get(), $u_email->get(), $u_pass->get(), $u_profile->get(), $u_display->get());

                    Util::step_message(array(STPMSG_USER_CREATE, 
                        array('Learn how it works' => build_link('about','spread'))));	
				}
				
				Util::auth_session($user);
				
				Util::user_message(MSG_USER_REGISTER);
				
				if (isset($req['pg'])) {
					shift_page($req['pg']);
				} else {
					shift_page('my', 'home');
				}
			} catch (Exception $e) {
				Util::catch_exception($e);
			}
		}
	}
	
?>
