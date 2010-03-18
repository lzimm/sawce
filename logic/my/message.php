<?php

	global $id;
	
	global $message;
	global $thread;
	global $body;
	global $submit;

	if ($id) {
		$message 	= Message::find($id);
		$thread 	= $message->get_thread();
		$my			= Util::as_authed();
		
		if ($message->_to == $my->_id) {
			$message->read();
		}
		
		if (($message->_from == $my->_id) || ($message->_to == $my->_id)) {
			$body		= new TextValidator('body', array('max_len' => 500, 'type' => 'text'));
			$submit		= new SubmitValidator('submit', array('label' => 'Reply'));
		
			if ($_POST) {
				try {
					$message = stripslashes($message->reply($body->get()));
					$thread = $message->get_thread();		
					
					Util::user_message(MSG_MESSAGE_SENT);
				} catch (Exception $e) {
					Util::catch_exception($e);	
				}
			}
		} else {
			Util::user_error(ERR_MESSAGE_PERMISSION);
			shift_page('my');
		}
	} else {
		Util::user_error(ERR_NO_MESSAGE_SELECTED);
		shift_page('my');		
	}
	
?>