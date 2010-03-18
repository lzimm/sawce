<?php

	global $id;
	global $action;
	global $key;
	global $req;
		
	if ($action == 'tag') {
		$_POST['tag'] = isset($_POST['tag']) ? $_POST['tag'] : (isset($req['as']) ? $req['as'] : '');
	}

	if (($action == 'tag') && (!Util::check_authed())) {
		if (isset($_POST['tag']) && (!isset($req['as']))) {
			shift_page('music','albums',$id,'tag','as:'.$_POST['tag']);
		} else {
			if (!isset($GLOBALS['forced_auth'])) {
				Util::user_error(ERR_NO_LOGIN);
			}
			
			require_login();
		}
	} else {
		if ($id) {
			global $albums;
			global $artist;
			global $tag;
			global $tag_add;
			
			$artist = Artist::find($id);
			if ($artist) {
				$albums = $artist->get_albums();
			} else {
				Util::user_error(ERR_NO_ARTIST_SELECTED);
				shift_page('music');				
			}
		} else {
			Util::user_error(ERR_NO_ARTIST_SELECTED);
			shift_page('music');
		}
	}
	
?>
