<?php

	$user = Util::as_authed();
	
	global $group;
	global $submit;
	
	$group 		= new TextValidator('group', array('max_len' => 11));
	$submit		= new SubmitValidator('join', array('label' => 'Join'));
	
	if ($_POST) {
		try {
			$user->join_group($group->get());
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
		
		Util::user_message(MSG_GROUP_JOIN);
	}
	
?>
