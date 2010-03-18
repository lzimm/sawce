<?php

	global $ref;
	global $songs;
	global $action;
    global $id;
    global $spread_link;

	if ($action == 'album') {
		$songs = Song::package('songs', 
				sprintf("album = '%s' GROUP BY songs.id ORDER BY songs.released DESC, songs.added DESC", 
					$id));
		$ref = 0;
        
        $spread_link = build_link('music','album',$id,'spread');
	} if ($action == 'playlist') {
        $songs = Song::package('playlist_songs', 
                sprintf("playlist_songs.playlist = '%s' GROUP BY songs.id ORDER BY songs.released DESC, songs.added DESC", 
                    $id));
        
        $owner = Playlist::get_user($id);
        
        $ref = $owner->_username;
        $spread_link = build_link('people','profile',$owner->_username,'spread');
    } else {
		$user = User::find($id);
	
		if ($user) {
			$songs = $user->sawce_get();
			$ref = $user->_username;
		}
        
        $spread_link = build_link('people','profile',$id,'spread');
	}
		
	$GLOBALS['ctype'] = CTYPE_SPREAD;
	
?>