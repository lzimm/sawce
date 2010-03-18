<?php

	global $transitional_menu;
	global $song;
	
	$transitional_menu = array();
	$transitional_menu['Play'] = array(sprintf("javascript:play_track(%s,%s);",$song->_artist, $song->_id), 
										'play_' . $song->_id, 'play_btn rbl play_' . $song->_id, '');
	$transitional_menu['Stop'] = array(sprintf("javascript:stop_track(%s);", $song->_id),
										'stop_' . $song->_id, 'stop_btn rbl stop_' . $song->_id, '');
	$transitional_menu['Download'] = build_download_link($song->_artist, $song->_id, $song->_secret);
	$transitional_menu['Spread'] = build_link('my','sawce',$song->_id,'add');
	$transitional_menu['Delete'] = build_link('my','album',$song->_album,'remove',$song->_id);
	
?>