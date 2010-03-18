<?php
    global $id;

    $song_id = substr($id, 0, strpos($id, '-'));
    $user = Util::as_authed();
    
    if ($user->check_rights($song_id)) {
        $song = Song::find($song_id);
        $song_title = $song->_display_name . ' - ' . $song->_song_name;
        $song_file = $GLOBALS['cfg']['basedir'] . 'songs/' . $song->_artist . '/' . $song->_id . '.mp3';
        
        if (file_exists($song_file)) {
            //header("Cache-Control: public, must-revalidate");
            //header("Pragma: hack"); // WTF? oh well, it works...
            header("Content-Type: application/octet-stream");
            header("Content-Length: " . (string)(filesize($song_file)) );
            header('Content-Disposition: attachment; filename="' . $song_title . '.mp3"');
            header("Content-Transfer-Encoding: binary\n");
            
            readfile($song_file);
        } else {
            Util::user_error(ERR_FILE_FAILURE_MISSING); 
            shift_page('music','song',$song_id);
        }        
    } else {
        Util::user_error(ERR_UNPURCHASED_SONG); 
        shift_page('music','song',$song_id);
    }
    
?>