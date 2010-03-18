<?php

	$type = Util::check_authed();
	
	global $ctype;
	
	global $user;
	global $status;
	global $u_status;
	global $u_submit;
	
	$user = Util::as_authed();
	$status = $user->get_status();
	
	$u_status = new TextValidator('status', array('preset' => $status['status']));
	
	if ($GLOBALS['ctype'] == CTYPE_AJAX) {
		$u_submit = new SubmitValidator('save', array('label' => 'Update', 'onClick' => 'return ipe(\'status\');'));
	} else {
		$u_submit = new SubmitValidator('save', array('label' => 'Update'));
	}
	
	if ($_POST) {
		try {
			$user->update_status($u_status->get());
			$status = $user->get_status();
			
			Util::user_message(MSG_USER_EDIT);
			
			if ($GLOBALS['ctype'] == CTYPE_AJAX) {
				render_custom('status_text');
			} else {
				shift_page('my');
			}
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
	}

?>
