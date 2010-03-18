<?php

	global $id;
	global $action;
	global $user;
	global $key;
	global $req;

	if ($user = Util::as_authed()) {
		if ($id) {
			global $song;
			global $artist;
	
			$song = Song::find($id);
			
			if ($song) {
				$artist = Artist::find_by_id($song->_artist);
				
				if (!$user->check_rights($song->_id) && !Util::cart_contains($song)) {				
					if ($action == 'add') {
						Util::cart_add($song, (isset($req['ref']))?$req['ref']:null);
						
						if ($GLOBALS['ctype'] == CTYPE_AJAL) {
							render_custom('cart_confirmed');
						} else {
							Util::user_message(MSG_SONG_CART);
							shift_page(isset($req['pg'])?$req['pg']:'base');
						}
					}
				} else if (Util::cart_contains($song)) {
					if ($GLOBALS['ctype'] == CTYPE_AJAL) {
						render_custom('cart_previous');
					} else {
						Util::user_error(ERR_SONG_RECART);
						shift_page(isset($req['pg'])?$req['pg']:'base');
					}					
				} else {
					if ($GLOBALS['ctype'] == CTYPE_AJAL) {
						render_custom('cart_owner');
					} else {
						Util::user_error(ERR_SONG_REPURCHASE);
						shift_page(isset($req['pg'])?$req['pg']:'base');
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
