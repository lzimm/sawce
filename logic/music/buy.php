<?php

	global $id;
	global $action;
	global $user;
	global $key;

	if ($user = Util::as_authed()) {
		if ($id) {
			global $song;
			global $artist;
			global $submit;
	
			$song = Song::find($id);
			
			$submit = new SubmitValidator('confirm', array('label' => 'Purchase'));
			
			if ($song) {
				$artist = Artist::find_by_id($song->_artist);
			
				if ($action == 'confirm') {
					try {
						$ip = $_SERVER['REMOTE_ADDR'];
						$user->purchase_song($id, $key, $ip);
						
						Util::user_message(MSG_SONG_PURCHASED);
                        Util::step_message(array(STPMSG_SONG_BUY,
                                array('Spread this song' => build_link('my','sawce',$id,'add'),
                                        'Download this song' => build_download_link($song->_artist, $song->_id, $song->_secret))));
                        
                        shift_page('music', 'song', $id);
					} catch (IllegalSongException $e) {						
						Util::user_error($e->getMessage());
						
						switch ($e->getMessage()) {
							case ERR_INSUFFICIENT_FUNDS:
								$data = 'min:' . $song->_price . ';next:music/buy/' . $id . '/via/' . $key;
								shift_page('my', 'balance', 'add', $data);
							break;
							
							case ERR_SONG_REPURCHASE:
								shift_page('music', 'song', $id);
							break;
						}
					} catch (Exception $e) {
						Util::catch_exception($e);
					}
				}
			} else {
				Util::user_error(ERR_NO_SONG_SELECTED);
				shift_page('music');
			}
		} else {
			Util::user_error(ERR_NO_SONG_SELECTED);
			shift_page('music');
		}
	} else {
		if (!isset($GLOBALS['forced_auth'])) {
			Util::user_error(ERR_NO_LOGIN);
		}
		
		require_login();
	}
	
?>
