<?php

	global $id;
	global $action;
	global $key;
	global $reqstring;
	global $req;
	
	global $grant;
	global $song;

	global $library;
	
	global $sort_page;
	global $sort_by;
	global $sort_order;
	
	global $search_song;
	global $search_album;
	global $search;
	
	global $next;
	
	if (Util::check_authed()) {
		if ($id && ($grant = User::find($id))) {		
			if (($type = Util::check_authed()) == UTYPE_ARTIST) {
				$user = Util::as_authed();
			
				if (is_numeric($action) && ($user->check_rights($action))) {					
					$song = Song::find($action);

					if (!$song->check_rights($grant->_id)) {
						if (($key == 'confirm') && $_POST) {
							try {
								$song->grant_rights($grant->_id, $user->_id, $_SERVER['REMOTE_ADDR']);
												
								Util::user_message(MSG_SONG_GRANTED);
								shift_page('people', 'profile', $id);
							} catch (Exception $e) {
								Util::catch_exception($e);
							}
						}							

						global $submit;						
						$submit = new SubmitValidator('confirm', array('label' => 'Confirm'));
						
						render_custom('give_confirm');
					} else {
						Util::user_error(ERR_SONG_REPURCHASE);
						shift_page('people', 'profile', $id);
					}
				} else {
					if (!$_POST && $req) {
						foreach($req as $search => $val) {
							$_POST['search_' . $search] = $val;
						}
					}
		
					$search_song 	= new TextValidator('search_song', array('required' => false));
					$search_album	= new TextValidator('search_album', array('required' => false));
					$search			= new SubmitValidator('submit', array('label' => 'Filter'));
	
					$library 	= array();
	
					$sorting = explode('-', $action);
					$sort_page = isset($sorting[0]) ? (int) $sorting[0] : 0;
					$sort_by = isset($sorting[1]) ? $sorting[1] : 'time';
					$sort_order = isset($sorting[2]) ? $sorting[2] : 'desc';
	
					try {
						$library	= $user->songs_my(50, $sort_page*50, $sort_by, $sort_order, $search_song->get(), $search_album->get(), $next);
						$reqstring = sprintf("song:%s;album:%s", $search_song->get(), $search_album->get());
					} catch (Exception $e) {
						Util::catch_exception($e);
					}
				}
			} else {
				Util::user_error(ERR_ARTIST_ONLY);
				shift_page('people', 'profile', $id);
			}
		} else {
			Util::user_error(ERR_NO_USER_SELECTED);
			shift_page('people');	
		}
	} else {
		Util::user_error(ERR_NO_LOGIN);
		require_login();		
	}
	
?>