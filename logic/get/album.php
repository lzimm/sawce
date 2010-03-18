<?php

	global $id;
	global $songs;

	$album = Album::find($id);
	$songs = $album ? $album->get_songs() : array();
	
	$GLOBALS['ctype'] = CTYPE_XML;
	
?>