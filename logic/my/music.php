<?php

	if (($type = Util::check_authed()) == UTYPE_ARTIST) {
		global $user;
		$user = Util::as_authed();
	} else {
		Util::user_error(ERR_ARTIST_ONLY);
		shift_page('my', 'home');
	}
	
?>
