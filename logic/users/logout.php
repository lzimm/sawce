<?php

	global $req;

	if ($type = Util::check_authed()) {
		Util::logout();
		Util::user_message(MSG_USER_LOGOUT);
	}
	
	if (isset($req['pg'])) {
		$page = explode('/', $req['pg']);
		$page = $page[0];
		
		if (in_array($page, $GLOBALS['public_pages'])) {
			shift_page($req['pg']);
		} else {
			shift_page('base');
		}
	} else {
		shift_page('base');
	}
	
?>
