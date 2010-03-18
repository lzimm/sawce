<?php

	global $transitional_menu;
	global $song;
	
	$transitional_menu = array();
	if (Util::check_authed() && Util::as_authed()->check_rights($song->_id)) {
        $transitional_menu['Play'] = array(sprintf("javascript:play_track(%s,%s,'%s');",$song->_artist, $song->_id, $song->_secret), 
                                            'play_' . $song->_id, 'play_btn rbl play_' . $song->_id, '');
        $transitional_menu['Stop'] = array(sprintf("javascript:stop_track(%s);", $song->_id),
                                        'stop_' . $song->_id, 'stop_btn rbl stop_' . $song->_id, '');
		$transitional_menu['Download'] = build_download_link($song->_artist, $song->_id, $song->_secret);
		$transitional_menu['Spread'] = array(build_link('my','sawce',$song->_id,'add'), 
											'buy_' . $song->_id, 'buy_btn rbl', '');
	} else {
        $transitional_menu['Play'] = array(sprintf("javascript:play_track(%s,%s);",$song->_artist, $song->_id), 
                                            'play_' . $song->_id, 'play_btn rbl play_' . $song->_id, '');
        $transitional_menu['Stop'] = array(sprintf("javascript:stop_track(%s);", $song->_id),
                                        'stop_' . $song->_id, 'stop_btn rbl stop_' . $song->_id, '');
		$transitional_menu['Buy'] = array(build_link('music','cart',$song->_id,'pg:music/song/'.$song->_id), 
											'buy_' . $song->_id, 'buy_btn rbl lbox', '');
	}
	
?>