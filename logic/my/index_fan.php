<?php

	global $id;
	global $req;

	global $user;
	global $status;
	global $library;
	global $sawce;
	global $artists;
	global $messages;
	global $earnings;

	$user 		= Util::as_authed();
	$status		= $user->get_status();
	$library	= $user->songs_get(5);
	$sawce		= $user->sawce_get(5);
	$artists	= $user->top_artists();
	$messages	= $user->get_messages();
	$earnings	= $user->get_daily_earnings();
	
	render_custom('index_fan');
	
?>