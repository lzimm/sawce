<?php

class Util {

	public static function render_song($song, $type = 'html', $pre = 'sw_') {
		if ($type == 'html') {
			$ret = sprintf("<div id='%ssong_%s' class='%ssong'>
							<span class='%ssong_name'>%s</span>
							<span class='%sdisplay_name'>%s</span>
							<span class='%sartist_status'>%s</span>
							<span class='%salbum_name'>%s</span>
							<span class='%ssong_price'>%s</span>
						</div>\n", 
							$pre, $song->_id, $pre, 
							$pre, $song->_song_name, 
							$pre, $song->_display_name,
							$pre, $song->_artist_status,
							$pre, $song->_album_name,
							$pre, $song->_price);
		} else if ($type == 'json') {
			$ret = sprintf("id: '%s',
							name: '%s',
							artist: '%s',
							artist_user: '%s',
							artist_name: '%s',
							artist_status: '%s',
							album: '%s',
							album_name: '%s',
							album_art: '%s',
							song_price: '%s'",
							$song->_id,
							$song->_song_name,
							$song->_artist,
							$song->_artist_user,
							$song->_display_name,
							$song->_artist_status,
							$song->_album,
							$song->_album_name,
							$song->_album_art,
							$song->_price);
		} else {
			$ret = sprintf("<%ssong>
							<%ssong_id>%s</%ssong_id>
							<%ssong_name>%s</%ssong_name>
							<%sartist>%s</%sartist>
							<%sartist_user>%s</%sartist_user>
							<%sdisplay_name>%s</%sdisplay_name>
							<%sartist_status>%s</%sartist_status>
							<%salbum>%s</%salbum>
							<%salbum_name>%s</%salbum_name>
							<%salbum_art>%s</%salbum_art>
							<%ssong_price>%s</%ssong_price>
							</%ssong>\n", 
							$pre, 
							$pre, $song->_id, $pre,
							$pre, $song->_song_name, $pre,
							$pre, $song->_artist, $pre,
							$pre, $song->_artist_user, $pre,
							$pre, $song->_display_name, $pre,
							$pre, $song->_artist_status, $pre,
							$pre, $song->_album, $pre,
							$pre, $song->_album_name, $pre,
							$pre, $song->_album_art, $pre,
							$pre, $song->_price, $pre,
							$pre);
		}

		return $ret;							
	}
	
	public static function clean_request($name, $action = false) {
		if (isset($_REQUEST[$name])) {
			return $_REQUEST[$name];
		} else {
			if ($action === true) {
				throw new Exception(sprintf(ERRTPL_MISSING_FIELD, $name));
			} else if ($action !== false) {
				return $action;
			}
		}
	}
	
	public static function auth_session($user) {
		$_SESSION['user'] = $user;
	}
	
	public static function check_authed() {
		if (isset($_SESSION['user'])) {
			if ($_SESSION['user'] instanceof Artist) {
				return UTYPE_ARTIST;
			} else if ($_SESSION['user'] instanceof User) {
				return UTYPE_FAN;
			}
		}
		
		return false;
	}
	
	public static function as_authed() {
		if (isset($_SESSION['user'])) {
			return $_SESSION['user'];
		} else {
			return false;
		}
	}
	
	public static function update_auth($user) {
		// only update the auth if we're logged in as might be
		// calling this through a profile edit via api	
		// also make sure that we're updating with the right user object	
		if (Util::check_authed()) {
			if ($user->__timestamp == $_SESSION['user']->__timestamp) {
				Util::auth_session($user);
			}
		}
	}
	
	public static function logout() {
		unset($_SESSION['user']);
		unset($_SESSION['cart']);
	}

	public static function user_error($error) {
		if ($error) {
			array_push($_SESSION['errors'], $error);
		}
	}
	
	public static function user_message($message) {
		if ($message) {
			array_push($_SESSION['messages'], $message);
		}
	}
    
    public static function step_message($message) {
        if ($message) {
            $_SESSION['step_message'] = $message;
        }
    }
	
	public static function process_messages($show = true) {
		echo '<div id="sw_msg">';
		
		if (isset($_SESSION['errors']) && $_SESSION['errors']) {
			while ($_SESSION['errors']) {
				if ($show) {
					printf("<div class='page_msg'><div class='editor_msg error'><span>%s</span></div><div class='clear'</div></div>", array_shift($_SESSION['errors']));
				} else {
					array_shift($_SESSION['errors']);
				}
			}
		}

		if (isset($_SESSION['messages']) && $_SESSION['messages']) {
			while ($_SESSION['messages']) {
				if ($show) {
					printf("<div class='page_msg'><div class='editor_msg'><span>%s</span></div><div class='clear'></div></div>", array_shift($_SESSION['messages']));
				} else {
					array_shift($_SESSION['messages']);
				}
			}
		}
		
		echo '</div>';
        
        if (isset($_SESSION['step_message'])) {
            global $lbmsg;
            
            $lbmsg = file_get_contents('incs/ajalheader.php');
            $lbmsg .= "<p class='lbmsg'>" . $_SESSION['step_message'][0] . "</p><p class='lbmsg'>";
            
            if (isset($_SESSION['step_message'][1])) {
                foreach ($_SESSION['step_message'][1] as $label => $link) {
                    $lbmsg .= '<a class="step_msg" href="'.$link.'"><span>'.$label.'</span></a>';
                }
            }
            
            $lbmsg .= '<a class="close_lb_step" href="javascript:lb_close();"><span>';
            $lbmsg .= ((isset($_SESSION['step_message'][2]) && $_SESSION['step_message'][2])?$_SESSION['step_message'][2]:'Close');
            $lbmsg .= '</span></a></p>'; 
            
            $lbmsg .= "<div class='clear'></div>";
        
            $lbmsg = str_replace("\n", "", addslashes($lbmsg)); 
            $lbmsg = str_replace("\r", "", $lbmsg); 
            
            unset($_SESSION['step_message']);
        }
	}
	
	public static function ajax_messages() {
		if (isset($_SESSION['errors']) && $_SESSION['errors']) {
			while ($_SESSION['errors']) {
				printf("<div class='editor_msg error'><span>%s</span></div>", array_shift($_SESSION['errors']));
			}
		}

		if (isset($_SESSION['messages']) && $_SESSION['messages']) {
			while ($_SESSION['messages']) {
				printf("<div class='editor_msg'><span>%s</span></div>", array_shift($_SESSION['messages']));
			}
		}
	}

	public static function catch_exception($e) {
		if (!$e instanceof DatabaseException && !$e instanceof DataException) {
			Util::user_error($e->getMessage());
		} else if ($e instanceof DataException) {
			Util::user_error(ERR_VALIDATION);
		} else if ($e instanceof DatabaseException){
			Util::user_error(ERR_DB_ERROR);
		}
	}
	
	public static function cart_add($song, $ref = null) {
		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
		}
		
		if ($song instanceof Song) {
			$song = $song->_id;
		}
		
		if (!in_array((int) $song, $_SESSION['cart'])) {
			$_SESSION['cart'][] = $ref?array((int) $song, $ref):(int) $song;
			return true;
		} else {
			return false;
		}
	}

	public static function cart_contains($song) {		
		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
			return false;
		}
		
		if ($song instanceof Song) {
			$song = $song->_id;
		}
		
		if (in_array((int) $song, $_SESSION['cart'])) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function cart_remove($song) {
		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
		}
		
		if ($song instanceof Song) {
			$song = $song->_id;
		}
		
		$tmp = array();
		foreach ($_SESSION['cart'] as $c_song) {
			if ($c_song != $song) $tmp[] = $c_song;
		}
		
		$_SESSION['cart'] = $tmp;
	}
	
	public static function cart_get() {
		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
		}
		
		return $_SESSION['cart'];		
	}
	
	public static function lost_password($email) {
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users WHERE email = '%s' LIMIT 1;", $email);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			Util::contact(
					$email,
					sprintf("Your password is: %s", $db->record['password']),
					'Your Password'
				);
				
			return true;
		} else {
			throw new UserException(ERR_NO_USER_WITH_EMAIL);
		}
	}

	public static function contact($to, $msg, $subject, $from = 'noreply@sawce.net', $from_name = 'Sawce') {
		$TXTmsg = $msg;
		$msg = str_replace("\n", "<br />", $msg);
		$HTMLmsg = $msg;
		
		$mail = new PHPMailer();
		
		$mail->From = $from;
		$mail->Sender = $from;
		$mail->FromName = $from_name;
		$mail->Subject = $subject;
		$mail->Body = $TXTmsg;
		$mail->AddAddress($to);
		
		$mail->Send();
	}

	public static function easy_clean($text = NULL) {
		$text = urldecode($text);
		$text = mysql_escape_string($text);
		$text = htmlentities($text);
		
		return $text;
	}
	
	public static function compute_taxes($value, $add = 0) {
		return round($value * ($GLOBALS['cfg']['rates']['tax'] + $add), 2);
	}
	
	public static function build_csv($array, $headings, $title = null) {
		if (!$title) {
			$title = date("Y-m-d");
		}
		
		$csv = '';
		$line_getter = '';
		foreach($headings as $index => $heading) {
			$csv .= ',' . $heading;
			$line_getter .= "\",\" . str_replace(',','.',\$line['" . $index . "']) . ";
		}
		$csv = substr($csv, 1) . "\n";
		$line_getter = '$csv .= ' . substr($line_getter, 6) . '"\n";';
		
		foreach($array as $i => $line) {
			eval($line_getter);
		}
		
		header("Content-type: application/vnd.ms-excel");
   		header("Content-disposition: attachment; filename=" . $title . ".csv");
   		echo $csv;

		die();
	}
    
    public static function spread_msg() {
        return !Util::check_authed();
    }

    
    public static function get_album_song_ids($album) {
        $db =& new SQL();
        
        $qry = sprintf("SELECT * FROM songs WHERE album = '%s';", $album);
        if (!$db->query($qry, SQL_ALL)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        $ids = array();
        foreach($db->record as $song) {
            $ids[] = $song['id'];
        }
        
        return $ids;
    }

    public static function get_sawce_song_ids($username) {
        $db =& new SQL();
        
        $qry = sprintf("SELECT song_sawce.* FROM song_sawce 
                            LEFT JOIN users ON users.id = song_sawce.user
                            WHERE users.username = '%s';", $username);
                            
        if (!$db->query($qry, SQL_ALL)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        $ids = array();
        foreach($db->record as $song) {
            $ids[] = $song['song'];
        }
        
        return $ids;
    }

}

?>
