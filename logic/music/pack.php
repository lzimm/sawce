<?php

	global $id;
	global $action;
	global $user;
	global $key;
	global $req;

	if ($user = Util::as_authed()) {
		if ($id) {
			global $songs;
			global $total;
			global $submit;
			
			$total = 0.00;
			$songs = array();
			
			$submit = new SubmitValidator('confirm', array('label' => 'Purchase'));
			
			if ($id == 'cart') {
				$ids = Util::cart_get();
			} else if ($id == 'album') {
                $ids = Util::get_album_song_ids($action);
            } else if ($id == 'sawce') {
                $ids = Util::get_sawce_song_ids($action);
                $key = $action;
            } else {
				$ids = split(',', $id);
			}
			
			$tmpids = '';
			foreach ($ids as $i) {
				if ((!isset($req['remove'])) || (trim($i) != $req['remove'])) {
					if (is_array($i)) {
						$i = $i[0];
					}
					
					$song = Song::find(trim($i));
				
					if ($song) {
						if (!$user->check_rights($i)) {
							$songs[] = $song;
							$total += $song->_price;
							$tmpids .= ',' . $i;
						}
					}
				} else {
					if ($id == 'cart') {
						Util::cart_remove(trim($i));
					}
				}
			}
			$id = ($id == 'cart')?'cart':substr($tmpids, 1);
			
			if ($songs) {
				if ($action == 'confirm') {
					if ($user->_balance < $total) {
						Util::user_error(ERR_INSUFFICIENT_FUNDS);
						
						$data = 'min:' . $total . ';next:music/pack/' . $id . '/via/' . $key;
						shift_page('my', 'balance', 'add', $data);
					} else {
						$success = 0;
						$failure = 0;
						foreach ($ids as $i) {
							try {
								$ref = $key;

								if (is_array($i)) {
									$ref = $i[1];
									$i = $i[0];
								}
								
								$ip = $_SERVER['REMOTE_ADDR'];
								$user->purchase_song(trim($i), $ref, $ip);
                                Util::cart_remove(trim($i));
								
								$success++;
							} catch (Exception $e) {
								Util::catch_exception($e);
								
								$failure++;
							}
						}
                        
						if ($success) {
							Util::user_message(sprintf(MSGTPL_SONGS_PURCHASED, $success));
                            Util::step_message(array(STPMSG_PACK_BUY));
						}
						
						if ($failure) {
							Util::user_error(sprintf(ERRTPL_SONGS_NOT_PURCHASED, $failure));
						}

                        shift_page('my', 'library');
					}
				}
			} else {
				Util::user_error(ERR_NO_SONG_SELECTED);
				shift_page('music');				
			}
		} else {
			Util::user_error(ERR_NO_SONG_SELECTED);
			shift_page('music');
		}
	} else {
		if (!isset($GLOBALS['forced_auth'])) {
			Util::user_error(ERR_NO_LOGIN);
		}
		
		require_login();
	}
	
?>
