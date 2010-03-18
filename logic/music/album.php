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
			shift_page('music','album',$id,'tag','as:'.$_POST['tag']);
		} else {
			if (!isset($GLOBALS['forced_auth'])) {
				Util::user_error(ERR_NO_LOGIN);
			}
			
			require_login();
		}
	} else {
		if ($id) {
			global $album;
			global $artist;
			global $tag;
			global $tag_add;
	
			$album = Album::find($id);
			
			if ($album) {
				$artist = Artist::find_by_id($album->_artist);
				
				$tag		= new TextValidator('tag', array('max_len' => 256));
				$tag_add	= new SubmitValidator('save', array('label' => 'Add'));
				
				switch($action) {
					case 'tag':
						try {
							$album->add_genre($tag->get(), Util::as_authed()->_id);
						} catch (Exception $e) {
							Util::catch_exception($e);
						}
					break;
                    
                    case 'spread':
                        if (Util::spread_msg()) {
                            show_lbmsg(LBMSG_ALBUM_SPREAD);
                        }
                    break;
				}
			} else {
				Util::user_error(ERR_NO_ALBUM_SELECTED);
				shift_page('music');
			}
		} else {
			Util::user_error(ERR_NO_ALBUM_SELECTED);
			shift_page('music');
		}
	}
	
?>
