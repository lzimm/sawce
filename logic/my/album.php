<?php

	global $id;
	global $action;
	global $key;
	global $req;

	if ($id) {
		if (($type = Util::check_authed()) == UTYPE_ARTIST) {
			global $user;
			global $album;
			global $album_name;
			global $album_art;
			global $album_submit;
			
			global $genre;
			global $genre_add;
			
			$user 		= Util::as_authed();
			$album 		= Album::find($id, $user->_id);
			
			if ($album) {
				$album_name 	= new TextValidator('album_name', array('max_len' => 256, 'default' => $album->_album_name));
				$album_art 		= new FileValidator('album_art', array(	'required' => false, 
																'path' => 'art_uploads'));
				$album_submit	= new SubmitValidator('save', array('label' => 'Save Changes'));
				
				$genre			= new TextValidator('genre', array('max_len' => 256, 'form' => 'genre'));
				$genre_add		= new SubmitValidator('save', array('label' => 'Add', 'form' => 'genre'));
																
				switch ($action) {
					case 'tag':
						try {
							$album->add_genre($genre->get());
						} catch (Exception $e) {
							Util::catch_exception($e);	
						}
					break;	
					
					case 'untag':
						if (isset($req['genre'])) {
							try {
								$album->remove_genre($req['genre']);
							} catch (Exception $e) {
								Util::catch_exception($e);	
							}
						}
					break;	

					case 'delete':
						try {
							$album->delete();
							
							Util::user_message(MSG_ALBUM_DELETED);
							
							shift_page('my', 'albums');
						} catch (Exception $e) {
							Util::catch_exception($e);	
						}
					break;
					
					case 'edit':
						if ($_POST) {
							try {
								$album->edit($album_name->get(), $album_art->get());
								
								Util::user_message(MSG_ALBUM_EDIT);
							} catch (Exception $e) {
								Util::catch_exception($e);
							}	
						}
					break;
					
					case 'remove':
						if ($key) {
							try {
								$song = Song::find($key, $user->_id);
								
								if ($song) {
									$song->delete();
									
									Util::user_message(MSG_ALBUM_EDIT);
								} else {
									Util::user_error(ERR_NO_SONG_SELECTED);
								}
							} catch (Exception $e) {
								Util::catch_exception($e);
							}	
						} else {
							Util::user_error(ERR_NO_SONG_SELECTED);
						}
					break;
				}				
			} else {
				Util::user_error(ERR_NO_ALBUM_SELECTED);
				shift_page('my', 'albums');
			}
		} else {
			Util::user_error(ERR_ARTIST_ONLY);
			shift_page('my', 'home');
		}
	} else {
		Util::user_error(ERR_NO_ALBUM_SELECTED);
		shift_page('my', 'albums');
	}
	
	if ($album) {
		define_logic('my', 'upload');
		
		global $song_album;
		
		$song_album = new HiddenValidator('song_album', array(	'form' => 'song_upload',
																'preset' => $id));
	}
	
?>
