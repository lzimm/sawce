<?php

	$user = Util::as_authed();
	
	global $group;
	global $submit;
			
	$group 		= new TextValidator('group', array('max_len' => 11));
	$submit		= new SubmitValidator('leave', array('label' => 'Leave'));
	
	if ($_POST) {
		try {
			$user->leave_group($group->get());
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
		
		Util::user_message(MSG_GROUP_LEAVE);
	}

?>
