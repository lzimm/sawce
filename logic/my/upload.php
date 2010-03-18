<?php

	if (($type = Util::check_authed()) == UTYPE_ARTIST) {
		global $user;
		global $song_album;
		global $song_name;
		global $song_submit;
		global $song;
		
		$user 			= Util::as_authed();
		
		$song_album 	= new SelectValidator('song_album', array(	'form' => 'song_upload',
																'label' => 'album_name', 
																'options' => $user->get_albums(FALSE)));
		$song_name 		= new TextValidator('song_name', array(		'form' => 'song_upload',
																'max_len' => 128));
		$song 			= new FileValidator('song', array(			'form' => 'song_upload',
																'path' => 'song_uploads'));
		$song_submit	= new SubmitValidator('upload', array('label' => 'Upload'));
		
		if ($_POST && isset($_POST['song_upload'])) {			
			try {				
				$new_song = Song::create($song->get(), $user->_id, $song_name->get(), $song_album->get());
				
				Util::user_message(MSG_SONG_UPLOAD);
                Util::step_message(array(STPMSG_SONG_UPLOAD, 
                    array('Upload Another Song' => build_link('my','album',$new_song->_album),
                            'Spread this Album' => build_link('embed','album',$new_song->_album))));
                
                shift_page('my','song',$new_song->_id);
			} catch (Exception $e) {
				Util::catch_exception($e);				
			}
		}

	} else {
		Util::user_error(ERR_ARTIST_ONLY);
		shift_page('my', 'home');
	}

?>
