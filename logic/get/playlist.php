<?php

	global $id;
	global $songs;

	$playlist = Playlist::find($id);
	$songs = $playlist ? $playlist->get_songs() : array();
	
	$GLOBALS['ctype'] = CTYPE_XML;
	
?>