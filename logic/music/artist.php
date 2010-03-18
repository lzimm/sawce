<?php

	global $id;
	global $action;

	if ($action == 'tag') {
		$_POST['tag'] = isset($_POST['tag']) ? $_POST['tag'] : (isset($req['as']) ? $req['as'] : '');
	}

	if (($action == 'tag') && (!Util::check_authed())) {
		if (isset($_POST['tag']) && (!isset($req['as']))) {
			shift_page('music','song',$id,'tag','as:'.$_POST['tag']);
		} else {
			if (!isset($GLOBALS['forced_auth'])) {
				Util::user_error(ERR_NO_LOGIN);
			}
			
			require_login();
		}
	} else {
		if ($id) {
			global $artist;
			global $albums;
			global $tag;
			global $tag_add;
		
			$artist = Artist::find($id);

			$tag		= new TextValidator('tag', array('max_len' => 256));
			$tag_add	= new SubmitValidator('save', array('label' => 'Add'));
		
			if ($artist) {
				$albums = $artist->get_albums();

				switch($action) {
					case 'tag':
						try {
							$artist->add_genre($tag->get(), Util::as_authed()->_id);
						} catch (Exception $e) {
							Util::catch_exception($e);
						}
                    break;  
                    
                    case 'spread':
                        if (Util::spread_msg()) {
                            show_lbmsg(LBMSG_ARTIST_SPREAD);
                        }
                    break;
				}
			} else {
				Util::user_error(ERR_NO_ARTIST_SELECTED);
				shift_page('music','artists');
			}
		} else {
			Util::user_error(ERR_NO_ARTIST_SELECTED);
			shift_page('music','artists');
		}
	}
	
?>
