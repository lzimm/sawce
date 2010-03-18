<?php

	global $id;
	global $action;

	global $user;
	global $status;
	global $library;
	global $sawce;
	global $spread;
	
	if ($id && ($user = User::find($id))) {
		$status		= $user->get_status();
		$library	= $user->songs_get(5);
		$sawce		= $user->sawce_get(5);
		$spread		= $user->songs_spread(5);
        
        if ($action && $action == 'spread' && Util::spread_msg()) {
            show_lbmsg(LBMSG_PROFILE_SPREAD);
        }
	} else {
		Util::user_error(ERR_NO_USER_SELECTED);
		shift_page('people');		
	}
	
?>