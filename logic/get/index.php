<?php

	global $user;
	global $songs;
    global $section;

	// remember this gets called no matter what get section is being processed
	// so we may have already hit albums
	if (!$songs) {
		$user = User::find($section);
		$songs = $user ? $user->sawce_get() : array();
	}
	
	$GLOBALS['ctype'] = CTYPE_XML;
	
?>