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
            
            $user         = Util::as_authed();
            $album         = Album::find($id, $user->_id);
            
            if ($album) {
                $album_name     = new TextValidator('album_name', array('max_len' => 256, 'default' => $album->_album_name));
                $album_art         = new FileValidator('album_art', array(    'required' => false, 
                                                                'path' => 'art_uploads'));
                $album_submit    = new SubmitValidator('save', array('label' => 'Save Changes'));
                
                $genre            = new TextValidator('genre', array('max_len' => 256, 'form' => 'genre'));
                $genre_add        = new SubmitValidator('save', array('label' => 'Add', 'form' => 'genre'));
                                                                
                global $zip;
                global $zip_submit;
            
                $zip        = new FileValidator('zip', array('path' => 'zip_uploads/' . $id));
                $zip_submit = new SubmitValidator('upload', array('label' => 'Upload'));
                
                if ($action == 'upload') {
					try {
						$async = Async::extraction_create($zip->get());
					} catch (Exception $e) {
						Util::catch_exception($e);
					}
	
                    shift_page('my', 'extract', $id, 'process', $async);
                } else if ($action == 'process') {
					if ($key) {
						try {
							if (!($msg = Async::extraction_check($key))) {
								header('Refresh: 5');
								show_lbmsg(LBMSG_ALBUM_EXTRACTING);
							} else {
								Util::user_message($msg);
                                Util::step_message(array(STPMSG_SONGS_EXTRACT,
                                    array('Spread this Album' => build_link('embed','album',$album->_id))));
							}
						} catch (Exception $e) {
							Util::catch_exception($e);
						}
					} else {
						Util::user_error(ERR_NO_KEY);
                		shift_page('my', 'album', $id);
					}
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
	
?>