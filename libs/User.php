<?php

class User {

	public $_id = NULL;
	public $_username = NULL;
	public $_email = NULL;
	public $_profile = NULL;
	public $_display_name = NULL;
	public $_art = NULL;
	public $_balance = NULL;
	public $_balance_total = NULL;
	public $_balance_pending = NULL;
	public $_db = NULL;
	
	public $__timestamp = NULL;

	public function __construct($params, $static = TRUE) {		
        foreach ($this as $key => $val) {
            if (isset($params[substr($key, 1)])) {
                $this->$key = $params[substr($key, 1)];
            }
        }
        
        if ($this->_profile && (!is_array($this->_profile))) {
            try {
                $this->_profile = is_array($tmp = unserialize($this->_profile)) ? $tmp : array();
            } catch (Exception $e) {
                $this->_profile = array();
            }
        } else if (!$this->_profile) {
			$this->_profile = array();
		}
        
        if (!$this->_display_name) {
            $this->_display_name = $this->_username;
        }
        
        $this->_db =& new SQL();
        $this->__timestamp = microtime(); 
		
		if ($static) {
			$this->_balance = sprintf("%1\$.2f", $this->_balance);
		} else {
			$this->_balance_total = sprintf("%1\$.2f", $this->_balance);
			$this->__update_balance();
		}
	}
	
	public function __destruct() {
		Util::update_auth($this);		
	}
	
	public static function create_from_array($array, $db = NULL, $static = TRUE) {
		if ($array['type'] == 'artist') {
			return new Artist($array, $static);
		} else {
			return new User($array,	$static);
		}
	}
	
	public static function create($username, $email, $password, $profile, $display_name, $balance = 0) {
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users WHERE username = '%s' OR email = '%s' LIMIT 1;", $username, $email);		
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}

		if (!$db->record) {
			$qry = sprintf("INSERT INTO users (username, email, password, profile, display_name, balance) 
				VALUES ('%s', '%s', '%s', '%s', '%s', '%s');",
				$username, $email, $password, $profile, $display_name, $balance);
			
			if ($db->query($qry)) {
				$id = $db->get_id();
                
				$user = new User(array(
                                    'id' => $id, 
                                    'username' => $username, 
                                    'email' => $email, 
                                    'profile' => $profile, 
                                    'display_name' => $display_name, 
                                    'balance' => $balance
                                    ));
                                
				$user->request_confirmation();
                $user->update_status();
				return $user;
			} else {
				throw new DatabaseException($db->error);
				return false;
			}
		} else {			
			if ($db->record['username'] == $username) {
				throw new UserExistsException(ERR_INUSE_USERNAME);
			}
			
			if ($db->record['email'] == $email) {
				throw new UserExistsException(ERR_INUSE_EMAIL);
			}
			
			return false;
		}
	}
	
	public static function find($user) {		
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users WHERE username = '%s' LIMIT 1;", $user);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			return User::create_from_array($db->record, $db);
		} else {
			return false;
		}
	}

	public static function find_by_id($id) {
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users WHERE id = '%s' LIMIT 1;", $id);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			return User::create_from_array($db->record, $db);
		} else {
			return false;
		}
	}

    public static function find_by_email($email) {
        $db =& new SQL();

        $qry = sprintf("SELECT * FROM users WHERE email = '%s' LIMIT 1;", $email);
        if (!$db->query($qry, SQL_INIT)) {
            throw new DatabaseException($db->error);
            return false;
        }
        
        if ($db->record) {
            return User::create_from_array($db->record, $db);
        } else {
            return false;
        }
    }

	public static function find_fb($fb) {
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users JOIN users_fb ON users.id = users_fb.user WHERE users_fb.fb_id = '%s' LIMIT 1;", $fb);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			return User::create_from_array($db->record, $db);
		} else {
			return false;
		}
	}

	public function set_fb($fb) {
		if (User::find_fb($fb)) {
			return false;
		}
		
		$qry = sprintf("INSERT INTO users_fb (fb_id, user) VALUES ('%s', '%s');", $fb, $this->_id);
		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}

	public static function find_os($id, $container) {
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users JOIN users_os ON users.id = users_os.user WHERE 
			users_os.os_id = '%s' AND users_os.os_container = '%s' LIMIT 1;", $id, $container);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			return User::create_from_array($db->record, $db);
		} else {
			return false;
		}
	}

	public function set_os($id, $container) {
		if (User::find_os($id, $container)) {
			return false;
		}
		
		$qry = sprintf("INSERT INTO users_os (os_id, os_container, user) VALUES ('%s', '%s', '%s');", $id, $container, $this->_id);
		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public static function find_authed($user, $token) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT users.* FROM users JOIN auth_sessions ON auth_sessions.user = users.username
			WHERE users.username = '%s' AND auth_sessions.token = '%s' AND auth_sessions.expires >= NOW() LIMIT 1;",
				$user, $token);

		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			return User::create_from_array($db->record, $db, false);
		} else {
			return false;
		}
		
		$qry = sprintf("UPDATE auth_sessions SET expires = (NOW() + INTERVAL 1 WEEK) WHERE token = '%s';", $token);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $user;
	}
	
	public static function auth($email, $password, $token = NULL) {
		$db =& new SQL();

		$qry = sprintf("SELECT * FROM users WHERE email = '%s' AND password = '%s' AND (active = '1' OR time >= '%s') LIMIT 1;", 
            $email, $password, date('Y-m-d H:i:s', time() - ($GLOBALS['cfg']['activation_period']*60*60)));
            
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if (!$db->record) {
			return false;
		}
		
		$user = User::create_from_array($db->record, $db, false);
		
		if ($token) {
			$qry = sprintf("INSERT INTO auth_sessions (user, token, expires) VALUES ('%s', '%s', (NOW() + INTERVAL 1 WEEK));",
				$db->record['id'], $token);
			
			if (!$db->query($qry)) {
				throw new DatabaseException($db->error);
			}			
		}
		
		return $user;
	}
    
    public static function verify($email, $confirm_key) {
        $db =& new SQL();
        
        $user = User::find_by_email($email);
        
        if ($user && ($confirm_key == substr(md5($user->_id . $GLOBALS['cfg']['secret']), 0, 15))) {
            $qry = sprintf("UPDATE users SET active = '1' WHERE id = '%s';", $user->_id);
            if (!$db->query($qry)) {
                throw new DatabaseException($db->error);
                return false;
            }
            
            return $user;
        }
        
        return false;
    }

    public function request_confirmation() {
        Util::contact(
                    $this->_email,
                    sprintf(EMLTPL_VERIFICATION_KEY, substr(md5($this->_id . $GLOBALS['cfg']['secret']), 0, 15)),
                    'Your Confirmation Key'
                );
    }    
    
	public function update_token($token) {
		$qry = sprintf("INSERT INTO auth_sessions (user, token, expires) VALUES ('%s', '%s', (NOW() + INTERVAL 1 WEEK));",
			$this->id, $token);
		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function edit_profile($profile) {        
		$qry = sprintf("UPDATE users SET profile = '%s' WHERE id = '%s';", 
            is_array($profile)?serialize($profile):$profile, $this->_id);
            
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_profile = $profile;
		
		Util::update_auth($this);
		
		return true;
	}

	public function edit_name($name) {
		$qry = sprintf("UPDATE users SET display_name = '%s' WHERE id = '%s';", $name, $this->_id);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_display_name = $name;
		
		Util::update_auth($this);
		
		return true;
	}

	public function confirm_membership($group) {
		$qry = sprintf("UPDATE artist_member SET status = 'active' WHERE artist = '%s' AND user = '%s' AND status = 'pending';", 
			$group, $this->_id);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function leave_group($group) {
		$qry = sprintf("UPDATE artist_member SET status = 'quit' WHERE artist = '%s' AND user = '%s';", $group, $this->_id);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;		
	}
	
	public function update_status($status = 'is spreading Sawce.') {
		$fail = false;

		$qry = "SET AUTOCOMMIT=0;";
        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        $qry = "START TRANSACTION;";
        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }

		$qry = sprintf("INSERT INTO user_status (user, status) VALUES ('%s', '%s');", $this->_id, $status);
		if (!$this->_db->query($qry)) {
			$error = $this->_db->error;
			$fail = true;
		}

		$qry = sprintf("UPDATE users SET status = '%s' WHERE id = '%s';", $status, $this->_id);
		if (!$this->_db->query($qry)) {
			$error = $this->_db->error;
			$fail = true;
		}
		
		if ($fail) {
			$this->_db->query("ROLLBACK;");
			throw new DatabaseException($error);
			return false;
		}
		
		$this->_db->query("COMMIT;");
		
		$qry = "SET AUTOCOMMIT=1;";
        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
		
		return true;
	}
	
	public function get_status($history = false) {
		$qry = sprintf("SELECT * FROM user_status WHERE user = '%s' ORDER BY time DESC%s;", $this->_id, ($history)?"":" LIMIT 1");
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		if ($this->_db->record) {
			if ($history) {
				$status_history = array();
				foreach($this->_db->record as $status) {
					$status_history[] = array("status" => $status['status'], "time" => $status['time']);
				}
				
				return status_history;
			} else {
				return array("status" => $this->_db->record[0]['status'], "time" => $this->_db->record[0]['time']);
			}
		} else {
			return false;
		}
	}

	public function check_rights($song) {
		$qry = sprintf("SELECT * FROM songs LEFT JOIN song_rights ON song_rights.song = songs.id
			WHERE (song_rights.song = '%s' AND song_rights.buyer = '%s') OR songs.artist = '%s' LIMIT 1;", $song, $this->_id, $this->_id);
		if (!$this->_db->query($qry, SQL_INIT)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}

		if ($this->_db->record) {
			return true;
		} else {
			return false;
		}
	}
	
	public function songs_get($limit = 0, $offset = 0, $order = '', $dir = '', $name = '', $album = '', $artist = '', &$next = null) {	
		switch ($order) {
			case 'song':
				$order = 'songs.song_name';
			break;
			
			case 'album':
				$order = 'albums.album_name';
			break;
			
			case 'artist':
				$order = 'users.display_name';
			break;
			
			default:
				$order = 'song_rights.time';
				$dir = $dir ? $dir : 'DESC';
			break;
		}
		
		$songs = Song::package("song_rights", sprintf("(song_rights.buyer = '%s' OR songs.artist = '%s')
			AND songs.song_name LIKE '%s' AND albums.album_name LIKE '%s' AND users.display_name LIKE '%s' 
			GROUP BY songs.id ORDER BY %s %s, songs.released DESC%s", 
			$this->_id, $this->_id, '%' . $name . '%', '%' . $album . '%', '%' . $artist . '%', $order, $dir,
			($limit ? sprintf(" LIMIT %s, %s", $offset, $limit + 1) : '')));
		
		if ($limit && sizeof($songs) > $limit) {
			$next = true;
			return array_slice($songs, 0, -1);
		}

		return $songs;
	}

	public function sawce_get($limit = 0, $offset = 0, &$next = null) {
		$songs = Song::package("song_sawce", sprintf("song_sawce.user = '%s' GROUP BY songs.id
			ORDER BY song_sawce.time DESC, songs.released DESC%s", 
			$this->_id, ($limit ? sprintf(" LIMIT %s, %s", $offset, $limit + 1) : '')));

		if ($limit && sizeof($songs) > $limit) {
			$next = true;
			return array_slice($songs, 0, -1);
		}
		
		return $songs;
	}
	
	public function sawce_add($song) {
		if ($this->check_rights($song)) {
			$qry = sprintf("INSERT INTO song_sawce (user, song) VALUES ('%s', '%s');", $this->_id, $song);
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			return true;
		} else {
			throw new IllegalSongException(ERR_UNPURCHASED_SONG);
			return false;
		}
	}
	
	public function sawce_remove($song) {
		$qry = sprintf("DELETE FROM song_sawce WHERE user = '%s' AND song = '%s';", $this->_id, $song);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function favorites_add($song) {
		if ($this->check_rights($song)) {
			$qry = sprintf("INSERT INTO song_favorites (user, song) VALUES ('%s', '%s');", $this->_id, $song);
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			return true;
		} else {
			throw new IllegalSongException(ERR_UNPURCHASED_SONG);
			return false;
		}
	}
	
	public function favorites_remove($song) {
		$qry = sprintf("DELETE * FROM song_favorites WHERE user = '%s' AND song = '%s';", $this->_id, $song);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function favorites_get() {
		return Song::package("song_favorites", sprintf("song_favorites.user = '%s' GROUP BY songs.id
			ORDER BY song_favorites.time DESC, songs.released DESC;", $this->_id));
	}
	
	public function add_balance($amount) {
		$this->__update_balance();
		
        $qry = "SET AUTOCOMMIT=0;";
        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        $qry = "START TRANSACTION;";
        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }

        try {
            $qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES ('%s', 'fill', '%s');", 
                $this->_id, $amount);
        
            if (!$this->_db->query($qry)) {        
                throw new DatabaseException($this->_db->error);
                return false;
            }
            
            $qry = sprintf("UPDATE users SET balance = '%s' WHERE id = '%s';", $this->_balance + $amount, $this->_id);
        
            if (!$this->_db->query($qry)) {        
                throw new DatabaseException($this->_db->error);
                return false;
            }
            
            $qry = "COMMIT;";
        } catch (Exception $e) {
            $qry = "ROLLBACK;";
            throw $e;
        }

        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        $qry = "SET AUTOCOMMIT=1;";
        if (!$this->_db->query($qry)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        $this->_balance += $amount;
        $this->_balance = sprintf("%1\$.2f", $this->_balance);

        Util::update_auth($this);
        
        return true;
    }

	public function withdraw_balance($amount) {
		$this->__update_balance();
		
		if (!(($amount - $this->_balance) > -7E-3)) {
	        $qry = "SET AUTOCOMMIT=0;";
	        if (!$this->_db->query($qry)) {
	            throw new DatabaseException($this->_db->error);
	            return false;
	        }
        
	        $qry = "START TRANSACTION;";
	        if (!$this->_db->query($qry)) {
	            throw new DatabaseException($this->_db->error);
	            return false;
	        }

	        try {
	            $qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES ('%s', 'withdraw', '%s');", 
	                $this->_id, $amount);
        
	            if (!$this->_db->query($qry)) {        
	                throw new DatabaseException($this->_db->error);
	                return false;
	            }
            
	            $qry = sprintf("UPDATE users SET balance = '%s' WHERE id = '%s';", $this->_balance - $amount, $this->_id);
        
	            if (!$this->_db->query($qry)) {        
	                throw new DatabaseException($this->_db->error);
	                return false;
	            }
            
	            $qry = "COMMIT;";
	        } catch (Exception $e) {
	            $qry = "ROLLBACK;";
	            throw $e;
	        }

	        if (!$this->_db->query($qry)) {
	            throw new DatabaseException($this->_db->error);
	            return false;
	        }
        
	        $qry = "SET AUTOCOMMIT=1;";
	        if (!$this->_db->query($qry)) {
	            throw new DatabaseException($this->_db->error);
	            return false;
	        }
        
	        $this->_balance -= $amount;
			$this->_balance = sprintf("%1\$.2f", $this->_balance);
        
	        Util::update_auth($this);
        
	        return true;
		} else {
			throw new DataException(ERR_INSUFFICIENT_FUNDS);
			return false;
		}
    }
	
	public function purchase_song($song_id, $vendor = NULL, $ip = NULL) {
		$this->__update_balance();

		if ($this->check_rights($song_id)) {
			throw new IllegalSongException(ERR_SONG_REPURCHASE);
			return false;
		}
		
		$song = Song::find($song_id);
		
		if (!(($this->_balance - $song->_price) < -7E-3)) {				
			if ($vendor && !is_int($vendor)) {
				$vendor_user = User::find($vendor);
				$vendor = $vendor_user ? $vendor_user->_id : NULL;
			}
			
			if ($vendor) {
				$vendor_user = $vendor_user ? $vendor_user : User::find_by_id($vendor);
				if (!$vendor_user->check_rights($song_id)) {
					$vendor = NULL;
				}
			}
			
			$qry = "SET AUTOCOMMIT=0;";
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			$qry = "START TRANSACTION;";
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}

			try {
				if ($this->__song_purchase($song, $song_id, $vendor, $ip)) {
					$qry = "COMMIT;";
				} else {
					$qry = "ROLLBACK;";
				}
			} catch (Exception $e) {
				$qry = "ROLLBACK;";
				throw $e;
			}

			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			$qry = "SET AUTOCOMMIT=1;";
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
		} else {
			throw new IllegalSongException(ERR_INSUFFICIENT_FUNDS);
		}
		
		return false;
	}
	
	private function __update_balance() {
		$qry = sprintf("SELECT * FROM users WHERE id = '%s' LIMIT 1;", $this->_id);

		if (!$this->_db->query($qry, SQL_INIT)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_balance_total = sprintf("%1\$.2f", $this->_db->record['balance']);
		
		$balance = $this->available_balance();
		$this->_balance = sprintf("%1\$.2f", $balance->_balance);
		$this->_balance_pending = sprintf("%1\$.2f", $balance->_pending);
		
		Util::update_auth($this);
		
		return true;
	}
	
	private function __song_purchase($song, $song_id, $vendor = NULL, $ip = NULL) {
		$qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES ('%s', 'debit', '%s');", 
			$this->_id, $song->_price);
		
		if (!$this->_db->query($qry)) {		
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$buy_entry = $this->_db->get_id();
		
		$qry = sprintf("UPDATE users SET balance = (balance - '%s') WHERE id = '%s';", $song->_price, $this->_id);
		if (!$this->_db->query($qry)) {		
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_balance -= $song->_price;

		Util::update_auth($this);
		
		if ($this->__song_sale($song, $this->_id, $buy_entry, $vendor, $ip)) {
			return true;
		}
	}
	
	private function __song_sale($song, $buyer, $buy_entry, $vendor = NULL, $ip = NULL) {
		if (!$vendor) {
			$vendor = $song->_artist;
		}
		
		if ($vendor == $song->_artist) {
			$this->__system_fee(floor($song->_price * $GLOBALS['cfg']['rates']['direct'] * 100)/100.00);
			
			$price = ceil($song->_price * (1 - $GLOBALS['cfg']['rates']['direct']) * 100)/100.00;
			
			$ref_entry = 0;

			$qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES ('%s', 'credit', '%s');",
				$vendor, $price);
			
			if (!$this->_db->query($qry)) {	
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			$sale_entry = $this->_db->get_id();
		} else {
			$price = floor($song->_price * $song->_commission * 100)/100.00;
			
			$this->__system_fee(floor($price * $GLOBALS['cfg']['rates']['commission']['seller'] * 100)/100.00);
			
			$price = ceil($price * (1 - $GLOBALS['cfg']['rates']['commission']['seller']) * 100)/100.00;

			if (!($sale_entry = $this->__song_royalty($song))) {		
				throw new IllegalSongException(ERR_UNRECORDED_LOYALTY);
				return false;
			}

			$qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES ('%s', 'credit', '%s');",
				$vendor, $price);
			
			if (!$this->_db->query($qry)) {	
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			$ref_entry = $this->_db->get_id();
		}

		$qry = sprintf("UPDATE users SET balance = (balance + '%s') WHERE id = '%s';", $price, $vendor);
		if (!$this->_db->query($qry)) {	
			throw new DatabaseException($this->_db->error);
			return false;
		}	
		
		$qry = sprintf("INSERT INTO song_rights (song, artist, buyer, vendor, buy_entry, sale_entry, ref_entry, ip) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", 
			$song->_id, $song->_artist, $buyer, $vendor,  $buy_entry, $sale_entry, $ref_entry, $ip);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;	
	}
	
	private function __system_fee($value) {
		$qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES (0, 'fee', '%s');",
			$value);
		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	private function __song_royalty($song) {
		$value = ceil($song->_price * (1 - $song->_commission) * 100)/100.00;
		
		$this->__system_fee(floor($value * $GLOBALS['cfg']['rates']['commission']['artist'] * 100)/100.00);
		
		$value = ceil($value * (1 - $GLOBALS['cfg']['rates']['commission']['artist']) * 100)/100.00;
		
		$qry = sprintf("INSERT INTO user_accounting (user, type, value) VALUES ('%s', 'credit', '%s');",
			$song->_artist, $value);
		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$sale_entry = $this->_db->get_id();
		
		$qry = sprintf("UPDATE users SET balance = (balance + '%s') WHERE id = '%s';", $value, $song->_artist);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $sale_entry;	
	}
	
	public function grant_rights($song_id, $vendor = NULL) {
		$song = Song::find($song);
		return $song->grant_rights($this->_id, $vendor);
	}
	
	public function top_artists($table = 'rights', $limit = 5, $offset = 0) {		
		$qry = sprintf("SELECT users.* FROM users
				JOIN songs ON songs.artist = users.id JOIN song_%s ON songs.id = song_%s.song
				WHERE song_%s.buyer = '%s' GROUP BY songs.artist ORDER BY count(songs.id) LIMIT %s, %s;",
				$table, $table, $table, $this->_id, $offset, $limit);
			
		if (!$this->_db->query($qry, SQL_ALL, new ArtistWrapperStrategy($this->_db))) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;
	}
	
	public function get_messages($limit = 5, $offset = 0) {
		return Message::get_messages_to($this->_id, $limit, $offset);
	}

	public function get_new_messages($limit = 0, $offset = 0) {
		return Message::get_messages_to($this->_id, $limit, $offset, 0);
	}
	
	public function send_message($from, $subject, $body, $type = 'message') {
		return Message::create($this->_id, $from, $subject, $body, $type);
	}
	
	public function get_hourly_earnings($starttime = null) {
		if (!$starttime) {
			$starttime = date('Y-m-d H:i:s', time());
		}
		
		$qry = sprintf("SELECT SUM(value) AS sum, time, HOUR(time) AS hour, DAYOFMONTH(time) AS day, MONTH(time) AS month, YEAR(time) AS year 
							FROM user_accounting WHERE type = 'credit' AND time >= TIMESTAMPADD(HOUR, -24, '%s') AND time <= '%s' AND user = '%s'
							GROUP BY HOUR(time) ORDER BY time DESC;", $starttime, $starttime, $this->_id);

		$max = new ComputeMaxStrategy('sum');

		if (!$this->_db->query($qry, SQL_ALL, $max)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_db->record[] = $max->get();
		
		return $this->_db->record;
	}

	public function get_daily_earnings($starttime = null) {
		if (!$starttime) {
			$starttime = date('Y-m-d H:i:s', time());
		}
		
		$qry = sprintf("SELECT SUM(value) AS sum, time, DAYOFMONTH(time) AS day, MONTH(time) AS month, YEAR(time) AS year 
							FROM user_accounting WHERE type = 'credit' AND time >= TIMESTAMPADD(DAY, -30, '%s') AND time <= '%s' AND user = '%s'
							GROUP BY DAY(time) ORDER BY time DESC;", $starttime, $starttime, $this->_id);

		$max = new ComputeMaxStrategy('sum');

		if (!$this->_db->query($qry, SQL_ALL, $max)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_db->record[] = $max->get();
		
		return $this->_db->record;
	}
	
	public function get_monthly_earnings($starttime = null) {
		if (!$starttime) {
			$starttime = date('Y-m-d H:i:s', time());
		}
		
		$qry = sprintf("SELECT SUM(value) AS sum, time, DAYOFMONTH(time) AS day, MONTH(time) AS month, YEAR(time) AS year 
							FROM user_accounting WHERE type = 'credit' AND time >= TIMESTAMPADD(MONTH, -12, '%s') AND time <= '%s' AND user = '%s'
							GROUP BY MONTH(time) ORDER BY time DESC;", $starttime, $starttime, $this->_id);

		$max = new ComputeMaxStrategy('sum');

		if (!$this->_db->query($qry, SQL_ALL, $max)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_db->record[] = $max->get();
		
		return $this->_db->record;
	}
	
	public function get_total_earnings() {
		$qry = sprintf("SELECT SUM(value) AS sum FROM user_accounting WHERE type = 'credit' AND user = '%s'
							GROUP BY user LIMIT 1;", $this->_id);

		if (!$this->_db->query($qry, SQL_INIT)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record['sum'];
	}

	public function get_total_payout() {
		$qry = sprintf("SELECT SUM(value) AS sum FROM user_accounting WHERE type = 'withdraw' AND user = '%s'
							GROUP BY user LIMIT 1;", $this->_id);

		if (!$this->_db->query($qry, SQL_INIT)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record['sum'];
	}
	
	public function get_sales_info($start_time = NULL, $end_time = NULL) {
		if (!$start_time) {
			$start_time = '0000-00-00 00:00:00';
		}
		
		if (!$end_time) {
			$end_time = date('Y-m-d H:i:s', time());
		}
		
		$qry = sprintf("SELECT user_accounting.*, buyer.username, songs.song_name, songs.id AS song FROM 
							user_accounting LEFT JOIN song_rights 
							ON (song_rights.ref_entry = user_accounting.id OR song_rights.sale_entry = user_accounting.id)
							LEFT JOIN users AS buyer ON buyer.id = song_rights.buyer
							LEFT JOIN songs ON song_rights.song = songs.id
							WHERE user_accounting.type = 'credit' AND user_accounting.user = '%s'
							AND user_accounting.time >= '%s' AND user_accounting.time <= '%s'
							ORDER BY time DESC;", $this->_id, $start_time, $end_time);
		
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;		
	}
	
	public function available_balance() {
		$qry = sprintf("SELECT * FROM user_accounting WHERE user = '%s';", $this->_id);
		
		$balance = new BalanceWrapperStrategy();
		if (!$this->_db->query($qry, SQL_ALL, $balance)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $balance;
	}
	
	public function songs_spread($limit = 5) {
		$qry = sprintf("SELECT song_rights.song AS song_id, songs.song_name as song_name, COUNT(DISTINCT song_rights.buyer) as spread
							FROM song_rights JOIN songs ON song_rights.song = songs.id
							WHERE song_rights.vendor = '%s' GROUP BY song_rights.song 
							ORDER BY COUNT(DISTINCT song_rights.buyer) DESC LIMIT %s;",
							$this->_id, $limit);
		
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;
	}

}

?>