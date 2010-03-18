<?php

	$type = Util::check_authed();
	
	global $user;
	global $u_profile;
	global $u_display;
	global $u_submit;
	
	$user = Util::as_authed();
    
	//$u_profile = new ProfileValidator('profile', array('preset' => $user->_profile));
    $u_profile = new TextValidator('profile', array('required' => FALSE, 'type' => 'text', 
                                    'default' => implode(",", is_array($user->_profile) ? $user->_profile : array())));
                                
	$u_display = new TextValidator('display_name', array('required' => FALSE, 'default' => $user->_display_name));	
	
	$u_submit = new SubmitValidator('save', array('label' => 'Save Changes'));

	if ($_POST) {
		try {
			$user->edit_profile(array_map("trim", explode(",", $u_profile->get())));
			$user->edit_name($u_display->get());
			
			Util::user_message(MSG_USER_EDIT);
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
	}

?>
