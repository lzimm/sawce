<?php

	$user = Util::as_authed();
	
	global $status;
	global $submit;
				
	$status = new TextValidator('status', array('max_len' => 256, 'preset' => $user->getStatus()));
	$submit	= new SubmitValidator('update', array('label' => 'Update'));

	if ($_POST) {
		try {
			$user->update_status($status->get());
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
		
		Util::user_message(MSG_STATUS_UPDATE);
	}
	
?>
