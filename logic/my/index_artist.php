<?php

	global $id;
	global $req;

	global $user;
	global $status;
	global $best_songs;
	global $new_fans;
	global $best_fans;
	global $messages;
	global $earnings;
	
	global $genre;
	global $genre_add;
	
	$user 		= Util::as_authed();
	$status		= $user->get_status();
	$best_songs	= $user->get_top_songs(5);
	$new_fans	= $user->get_fans(5, '', 'MIN(song_rights.time)');
	$best_fans	= $user->get_fans(5, '', 'COUNT(DISTINCT song_rights.buyer)');
	$messages	= $user->get_messages();
	$earnings	= $user->get_daily_earnings();

	$genre		= new TextValidator('genre', array('max_len' => 256, 'form' => 'genre'));
	$genre_add	= new SubmitValidator('save', array('label' => 'Add', 'form' => 'genre'));
	
	switch ($id) {
		case 'tag':
			try {
				$user->add_genre($genre->get());
			} catch (Exception $e) {
				Util::catch_exception($e);	
			}					
		break;
		
		case 'untag':
			if (isset($req['genre'])) {
				try {
					$user->remove_genre($req['genre']);
				} catch (Exception $e) {
					Util::catch_exception($e);	
				}
			}
		break;
	}
	
	render_custom('index_artist');
	
?>