<?php

	global $id;

	if (($type = Util::check_authed()) == UTYPE_ARTIST) {
		global $user;
		global $albums;
		global $album_name;
		global $album_submit;
		
		$user 			= Util::as_authed();
		$album_name 	= new TextValidator('album_name', array('max_len' => 256));
		$album_submit	= new SubmitValidator('create', array(	'label' => 'Create Album',
																'onClick' => /*'return sw_update();'*/''));
		
		if ($id == 'create') {
			if ($_POST) {
				try {
					global $new_album;
					
					$new_album = Album::create($user->_id, $album_name->get());
					
					Util::user_message(MSG_ALBUM_CREATED);
                    Util::step_message(array(STPMSG_ALBUM_CREATED));
                    
				    shift_page('my','album',$new_album->_id);
                } catch (Exception $e) {
					Util::catch_exception($e);				
				}
			}
		}
		
		$albums = $user->get_albums();

	} else {
		Util::user_error(ERR_ARTIST_ONLY);
		shift_page('my', 'home');
	}
	
?>
