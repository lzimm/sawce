<?php

	global $id;
	global $action;
	global $key;
	global $req;

	if ($id) {
		if (($type = Util::check_authed()) == UTYPE_ARTIST) {
			global $user;
			global $song;
			global $song_name;
			global $song_album;
			global $song_price;
			global $song_share;
			global $song_save;
			
			global $genre;
			global $genre_add;
			
			global $similar_songs;
			global $spreaders;
			
			$user	= Util::as_authed();
			$song 	= Song::find($id, $user->_id);

			if ($song) {
				$similar_songs = $song->get_similar_songs();
				$spreaders = $song->get_spreaders();
				
				$song_price = new SelectValidator('song_price', array(	'label' => 'rate', 
																		'options' => array(
																			array('id' => '0.00', 'rate' => '$0.00'),
																			array('id' => '0.39', 'rate' => '$0.39'),
																			array('id' => '0.49', 'rate' => '$0.49'),
																			array('id' => '0.59', 'rate' => '$0.59'),
																			array('id' => '0.69', 'rate' => '$0.69'),
																			array('id' => '0.79', 'rate' => '$0.79'),
																			array('id' => '0.89', 'rate' => '$0.89'),
																			array('id' => '0.99', 'rate' => '$0.99'),
																			array('id' => '1.09', 'rate' => '$1.09'),
																			array('id' => '1.19', 'rate' => '$1.19'),
																			array('id' => '1.29', 'rate' => '$1.29'),
																			array('id' => '1.39', 'rate' => '$1.39'),
																			array('id' => '1.49', 'rate' => '$1.49'),
																			array('id' => '1.59', 'rate' => '$1.59'),
																			array('id' => '1.69', 'rate' => '$1.69'),
																			array('id' => '1.79', 'rate' => '$1.79'),
																			array('id' => '1.89', 'rate' => '$1.89'),
																			array('id' => '1.99', 'rate' => '$1.99')
																		),
																		'default' => $song->_price));
				$song_share = new SelectValidator('song_share', array(	'label' => 'rate', 
																		'options' => array(
																			array('id' => '0.00', 'rate' => '0%'),
																			array('id' => '0.10', 'rate' => '10%'),
																			array('id' => '0.20', 'rate' => '20%'),
																			array('id' => '0.30', 'rate' => '30%'),
																			array('id' => '0.40', 'rate' => '40%'),
																			array('id' => '0.50', 'rate' => '50%'),
																			array('id' => '0.60', 'rate' => '60%'),
																			array('id' => '0.70', 'rate' => '70%'),
																			array('id' => '0.80', 'rate' => '80%'),
																			array('id' => '0.90', 'rate' => '90%'),
																			array('id' => '1.00', 'rate' => '100%'),
																		),
																		'note' => FNOTE_SONG_COMMISSION,
																		'default' => $song->_commission));
				$song_album = new SelectValidator('song_album', array(	'label' => 'album_name', 
																		'options' => $user->get_albums(FALSE),
																		'default' => $song->_album));
				$song_name 	= new TextValidator('song_name', array(		'max_len' => 128,
																		'default' => $song->_song_name));
				$song_save	= new SubmitValidator('song_save', array(	'label' => 'Save Changes'));
				
				$genre		= new TextValidator('genre', array('max_len' => 256, 'form' => 'genre'));
				$genre_add	= new SubmitValidator('save', array('label' => 'Add', 'form' => 'genre'));
				
				switch ($action) {
					case 'tag':
						try {
							$song->add_genre($genre->get());
						} catch (Exception $e) {
							Util::catch_exception($e);	
						}					
					break;

					case 'untag':
						if (isset($req['genre'])) {
							try {
								$song->remove_genre($req['genre']);
							} catch (Exception $e) {
								Util::catch_exception($e);	
							}
						}
					break;
					
					case 'delete':
						try {
							$song->delete();
							
							Util::user_message(MSG_SONG_DELETED);
							
							shift_page('my', 'album', $song->_album);
						} catch (Exception $e) {
							Util::catch_exception($e);	
						}
					break;
					
					case 'edit':
						if ($_POST) {
							try {
								$song->edit($song_name->get(), $song_album->get(), $song_price->get(), $song_share->get());
								
								$song = Song::find($id);
								
								Util::user_message(MSG_SONG_EDIT);
							} catch (Exception $e) {
								Util::catch_exception($e);
							}	
						}
					break;
				}				
			} else {
				Util::user_error(ERR_NO_SONG_SELECTED);
				shift_page('my', 'albums');
			}
		} else {
			Util::user_error(ERR_ARTIST_ONLY);
			shift_page('my', 'home');
		}
	} else {
		Util::user_error(ERR_NO_SONG_SELECTED);
		shift_page('my', 'albums');
	}
	
?>
