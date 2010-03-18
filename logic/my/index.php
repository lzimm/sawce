<?php

	if (Util::as_authed() instanceof Artist) {
		define_logic('my', 'index_artist');
	} else {
		define_logic('my',  'index_fan');
	}
	
?>