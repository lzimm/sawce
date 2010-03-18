<?php

	global $id;
	global $next;
	global $req;
	global $reqstring;
	global $action;
	global $message_header;
	
	global $user;
	global $messages;
	
	$user 		= Util::as_authed();
	
	switch ($action) {
		case 'sent':
			$where = sprintf("user_from.id = '%s'", $user->_id);
			$message_header = 'Sent Messages';
		break;

		case 'new':
			$where = sprintf("user_to.id = '%s' AND user_messages.read = '0'", $user->_id);
			$message_header = 'New Messages';
		break;
		
		case 'inbox':
		default:
			$where = sprintf("user_to.id = '%s'", $user->_id);
			$message_header = 'Inbox';
		break;
	}
	
	$messages	= Message::get_messages_where($where, 10, $id*10, $next);
	
?>