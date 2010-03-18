<?php

	if (Util::check_authed()) {
		shift_page('my', 'home');
	} else {
		global $u_email;
		global $u_pass;
		global $u_submit;
	
		$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
		$u_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
		$u_submit	= new SubmitValidator('login', array('label' => 'Login'));
		
		global $r_name;
		global $r_pass;
		global $r_pass_chk;
		global $r_email;
		global $r_display;
		global $r_profile;
		global $r_type;
		global $r_check;
		global $r_submit;
	
		$r_name 	= new TextValidator('name', array('max_len' => 16, 'note' => FNOTE_USER_NAME));
		$r_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
		$r_pass_chk	= new TextValidator('pass_chk', array('max_len' => 64, 'password' => TRUE));
		$r_email 	= new TextValidator('email', array(), new EmailValidationStrategy());
		$r_display	= new TextValidator('display_name', array('required' => FALSE, 'note' => FNOTE_DISPLAY_NAME));
		$r_profile	= new ProfileValidator('profile', array('required' => FALSE));	
		$r_type 	= new SelectValidator('type', array('max_len' => 12, 'default' => UTYPE_FAN,
													'options' => array(
														array('id' => 'fan', 'name' => 'Fan'),
														array('id' => 'artist', 'name' => 'Artist')
													)));
		$r_check	= new CheckValidator('check', array('lookfor' => 'email'));
		$r_submit	= new SubmitValidator('reqister', array('label' => 'Register'));
	}
	
?>
