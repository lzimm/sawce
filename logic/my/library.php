<?php

	global $id;
	global $action;
	global $key;
	global $reqstring;
	global $req;

	global $user;
	global $library;
	
	global $search_song;
	global $search_album;
	global $search_artist;
	global $search;
	
	global $next;
	
	if (!$_POST && $req) {
		foreach($req as $search => $val) {
			$_POST['search_' . $search] = $val;
		}
	}

	$search_song 	= new TextValidator('search_song', array('required' => false));
	$search_album	= new TextValidator('search_album', array('required' => false));
	$search_artist	= new TextValidator('search_artist', array('required' => false));
	$search			= new SubmitValidator('submit', array('label' => 'Filter'));
	
	$user 		= Util::as_authed();
	$library 	= array();
	
	$id = $id ? (int) $id : 0;
	
	try {
		$library	= $user->songs_get(50, $id*50, $action, $key, $search_song->get(), $search_album->get(), $search_artist->get(), $next);
		$reqstring = sprintf("song:%s;album:%s;artist:%s", $search_song->get(), $search_album->get(), $search_artist->get());
	} catch (Exception $e) {
		Util::catch_exception($e);
	}
	
?>