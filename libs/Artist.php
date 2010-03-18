<?php

class Artist extends User {

    public function __construct($params, $static = TRUE) {	    
	    parent::__construct($params, $static);
	}

	public function __destruct() {
		parent::__destruct();
	}

	public static function create($username, $email, $password, $profile, $display_name, $balance = 0) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT * FROM users WHERE username = '%s' OR email = '%s' LIMIT 1;", $username, $email);
		$db->query($qry, SQL_INIT);

		if (!$db->record) {
			$qry = sprintf("INSERT INTO users (username, email, password, profile, display_name, balance, type) 
				VALUES ('%s', '%s', '%s', '%s', '%s', '%s', 'artist');",
				$username, $email, $password, $profile, $display_name, $balance);
			
			if ($db->query($qry)) {
				$id = $db->get_id();
                
				$artist = new Artist(array(
                                        'id' => $id, 
                                        'username' => $username, 
                                        'email' => $email, 
                                        'profile' => $profile, 
                                        'display_name' => $display_name, 
                                        'balance' => $balance
                                        ));

                $artist->request_confirmation();
				$artist->update_status();
				$artist->create_album('Singles');
				return $artist;
			} else {
				throw new DatabaseException($db->error);
			}
		} else {
			if ($db->record['username'] == $username) {
				throw new UserExistsException(ERR_INUSE_USERNAME);
			}
			
			if ($db->record['email'] == $email) {
				throw new UserExistsException(ERR_INUSE_EMAIL);
			}
		}
	}

	public static function find($user) {
		$artist = parent::find($user);
		
		if ($artist instanceof Artist) {
			return $artist;
		} else {
			return false;
		}
	}
	
	public static function search($string, $offset = 0, $prefix = false, &$next) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT * FROM users 
				WHERE display_name LIKE '%s' AND type = 'artist' ORDER BY display_name LIMIT %s, 26;",
			$prefix?$string.'%':'%'.$string.'%', $offset);
			
		$db->query($qry, SQL_ALL, new ArtistWrapperStrategy($db), 25, $next);
		
		return $db->record;
	}

	public static function find_by_id($id) {		
		$artist = parent::find_by_id($id);
		
		if ($artist instanceof Artist) {
			return $artist;
		} else {
			return false;
		}
	}

	public static function find_authed($user, $token) {
		$artist = parent::find_authed($user, $token);
		
		if ($artist instanceof Artist) {
			return $artist;
		} else {
			return false;
		}
	}

	public static function auth($email, $password, $token = NULL) {
		$artist = parent::auth($email, $password, $token);
		
		if ($artist instanceof Artist) {
			return $artist;
		} else {
			return false;
		}
	}
	
	public static function like($artist, $limit = 10) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT like_artists.* FROM users AS like_artists LEFT JOIN songs AS like_songs ON like_songs.artist = like_artists.id
							LEFT JOIN song_rights as like_rights ON like_rights.song = like_songs.id
							LEFT JOIN song_rights as my_rights ON like_rights.buyer = my_rights.buyer
							LEFT JOIN songs AS my_songs ON my_rights.song = my_songs.id
							LEFT JOIN users AS me ON my_songs.artist = me.id WHERE me.username = '%s'
							GROUP BY like_artists.id ORDER BY count(DISTINCT my_rights.buyer) DESC, count(DISTINCT like_rights.buyer) DESC LIMIT %s;",
							$artist, $limit);
	
		if (!$db->query($qry, SQL_ALL, new ArtistWrapperStrategy())) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;
	}
	
	public function get_default_album() {
		return Album::get_artist_default($this->_id);
	}

	public function invite_member($user) {
		$qry = sprintf("SELECT * FROM artist_member WHERE artist = '%s' AND user = '%s' LIMIT 1;", $this->_id, $user);
		if (!$this->_db->query($qry, SQL_INIT)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		if ($this->_db->record) {
			if ($this->_db->record['status'] == 'active') {
				$qry = "SELECT 1;";
			} else {
				$qry = sprintf("UPDATE artist_member SET status = 'pending' WHERE artist = '%s' AND user = '%s';", $this->_id, $user);
			}
		} else {
			$qry = sprintf("INSERT INTO artist_member (artist, user) VALUES ('%s', '%s');", $this->_id, $user);
		}
		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function get_members($type = 'active') {
		$qry = sprintf("SELECT * FROM artist_member JOIN users ON artist_member.user = users.id 
						WHERE artist_member.status = '%s' AND artist_member.artist = '%s';", $type, $this->_id);
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$members = array();
		foreach($this->_db->query as $member) {
			$members[] = new User($member);
		}
		
		return $members;
	}
	
	public function get_genres($artist = true) {
		$qry = sprintf("SELECT * FROM artist_genre WHERE artist = '%s' AND user %s GROUP BY genre;", 
			$this->_id, ($artist ? '= ' : '!= ') . $this->_id);
		
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$genres = array();
		foreach($this->_db->record as $genre) {
			$genres[] = $genre['genre'];
		}
		
		return $genres;
	}
	
	public function add_genre($genre, $user = null) {
		if (!$user) {
			$user = $this->_id;
		}
		
		$qry = sprintf("INSERT INTO artist_genre (artist, genre, user) VALUES ('%s', '%s', '%s');", 
			$this->_id, $genre, $user);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function remove_genre($genre, $artist = true) {
		$qry = sprintf("DELETE FROM artist_genre WHERE artist = '%s' AND genre LIKE '%s' AND user %s;", 
			$this->_id, $genre, ($artist ? '= ' : '!= ') . $this->_id);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function create_album($name, $released = NULL) {
		$album = Album::create($this->_id, $name, $released);
		return $album;
	}
	
	public function get_albums($objects = TRUE, $limit = 0) {
		return Album::by_artist($this->_id, $objects, $limit);
	}

	public function get_similar_artists() {
		$qry = sprintf("SELECT their.* FROM users AS my 
						LEFT JOIN songs AS my_songs ON my_songs.artist = my.id
						LEFT JOIN song_rights AS my_sales ON my_sales.song = my_songs.id
						LEFT JOIN song_rights AS their_sales ON my_sales.buyer = their_sales.buyer
						LEFT JOIN songs AS their_songs ON their_sales.song = their_songs.id
						LEFT JOIN users AS their ON their_songs.artist = their.id
						WHERE my.id = '%s' AND their.id != '%s' AND their.id IS NOT NULL
						GROUP BY their.id ORDER BY count(*) DESC;", $this->_id, $this->_id);	
							
		if (!$this->_db->query($qry, SQL_ALL, new ArtistWrapperStrategy($this->_db))) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;		
	}

    public function get_mavens($limit = 10) {
        $qry = sprintf("SELECT usr_2.* FROM users AS usr_1 LEFT JOIN song_rights AS sr_1 ON sr_1.vendor = usr_1.id
                        LEFT JOIN song_rights AS sr_2 ON (sr_2.buyer = sr_1.vendor AND sr_2.artist = sr_1.artist)
                        LEFT JOIN users AS usr_2 ON sr_2.vendor = usr_2.id 
                        WHERE sr_1.artist = '%s' AND usr_2.id IS NOT NULL GROUP BY usr_1.id 
                        ORDER BY count(DISTINCT sr_1.buyer) DESC, sr_2.time ASC
                        LIMIT %s;", $this->_id, $limit);
                        
        if (!$this->_db->query($qry, SQL_ALL)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }
        
        return $this->_db->record;    
    }
    
	public function get_spreaders($limit = 10) {
		// this one will get the most important spreader for a single song
		/*$qry = sprintf("SELECT usr_1.username AS connector, usr_2.username AS maven, count(DISTINCT sr_1.buyer) AS count 
						FROM users AS usr_1 LEFT JOIN song_rights AS sr_1 ON sr_1.vendor = usr_1.id
						LEFT JOIN song_rights AS sr_2 ON sr_2.buyer = sr_1.vendor AND sr_2.song = sr_1.song
						LEFT JOIN users AS usr_2 ON sr_2.vendor = usr_2.id
						LEFT JOIN songs ON sr_1.song = songs.id LEFT JOIN users AS artist ON artist.id = songs.artist
						WHERE artist.id = '%s' AND usr_2.id IS NOT NULL GROUP BY usr_1.id ORDER BY count(DISTINCT sr_1.buyer) DESC 
						LIMIT %s;", $this->_id, $limit);*/
		
		$qry = sprintf("SELECT usr_1.username AS connector, usr_2.username AS maven, count(DISTINCT sr_1.buyer) AS count 
						FROM users AS usr_1 LEFT JOIN song_rights AS sr_1 ON sr_1.vendor = usr_1.id
						LEFT JOIN song_rights AS sr_2 ON (sr_2.buyer = sr_1.vendor AND sr_2.artist = sr_1.artist)
						LEFT JOIN users AS usr_2 ON sr_2.vendor = usr_2.id 
						WHERE sr_1.artist = '%s' AND usr_2.id IS NOT NULL GROUP BY usr_1.id 
						ORDER BY count(DISTINCT sr_1.buyer) DESC, sr_2.time ASC
						LIMIT %s;", $this->_id, $limit);
						
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;		
	}
	
	public function get_top_songs($limit = 10) {
		$qry = sprintf("songs.artist = '%s' GROUP BY song_rights.song ORDER BY count(song_rights.buyer) DESC", $this->_id);
		return Song::package('song_rights', $qry, $limit);
	}
	
	public function get_fans($limit = 50, $filter_string = '', $order = '', $where_string = '', $wrap = true) {
		if (!$order) {
			$order = 'COUNT(DISTINCT song_rights.song)';
		}
		
		/*$qry = sprintf("SELECT users.*, count(DISTINCT song_rights.id) AS `count`, count(DISTINCT song_rights.buyer) AS `spread`, 
						max(song_rights.time) AS `last`, min(song_rights.time) AS `first`
						FROM users LEFT JOIN song_rights ON song_rights.buyer = users.id 
						LEFT JOIN songs ON song_rights.song = songs.id WHERE
						songs.artist = '%s'%s AND users.id = IF( song_rights.vendor = songs.artist, song_rights.buyer, song_rights.vendor ) 
						GROUP BY users.id%s ORDER BY %s DESC%s;",
						$this->_id, $where_string?sprintf(' AND %s', $where_string):'', 
						$filter_string?sprintf(' HAVING %s', $filter_string):'', $order, $limit?sprintf(' LIMIT %s', $limit):'');*/
		
		/*$qry = sprintf("SELECT users.*, IF( song_rights.vendor = songs.artist, song_rights.buyer, song_rights.vendor ) AS 'grouping',
						count(DISTINCT song_rights.id) AS `count`, (count(DISTINCT song_rights.buyer) - 1) AS `spread`, 
						max(song_rights.time) AS `last`, min(song_rights.time) AS `first`
						FROM songs RIGHT JOIN song_rights ON songs.id = song_rights.song
						RIGHT JOIN users ON IF( song_rights.vendor = songs.artist, song_rights.buyer, song_rights.vendor ) = users.id 
						WHERE songs.artist = '%s'%s GROUP BY grouping%s ORDER BY %s DESC%s;",
						$this->_id, $where_string?sprintf(' AND %s', $where_string):'', 
						$filter_string?sprintf(' HAVING %s', $filter_string):'', $order, $limit?sprintf(' LIMIT %s', $limit):'');*/
		
		$qry = sprintf("SELECT users.*, count(DISTINCT song_rights.song) AS `count`, (count(DISTINCT song_rights.buyer) - 1) AS `spread`, 
						max(song_rights.time) AS `last`, min(song_rights.time) AS `first`
						FROM users LEFT JOIN song_rights ON (song_rights.vendor = users.id OR song_rights.buyer = users.id) 
						WHERE song_rights.artist = '%s' AND users.id != song_rights.artist%s GROUP BY users.id%s ORDER BY %s DESC%s;",
						$this->_id, $where_string?sprintf(' AND %s', $where_string):'', 
						$filter_string?sprintf(' HAVING %s', $filter_string):'', $order, $limit?sprintf(' LIMIT %s', $limit):'');

		if (!$this->_db->query($qry, SQL_ALL, $wrap ? new FanWrapperStrategy() : null)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;		
	}

	public function get_fan_count($filter_string = '', $where_string = '') {
		$qry = sprintf("SELECT COUNT(DISTINCT selection.id) AS `selection_count` FROM (SELECT users.*,
						count(DISTINCT song_rights.song) AS `count`, (count(DISTINCT song_rights.buyer) - 1) AS `spread`, 
						max(song_rights.time) AS `last`, min(song_rights.time) AS `first` FROM users
						LEFT JOIN song_rights ON (song_rights.vendor = users.id OR song_rights.buyer = users.id) 
						WHERE song_rights.artist = '%s' AND users.id != song_rights.artist%s GROUP BY users.id%s) AS selection;",
						$this->_id, $where_string?sprintf(' AND %s', $where_string):'', 
						$filter_string?sprintf(' HAVING %s', $filter_string):'');

		if (!$this->_db->query($qry, SQL_INIT)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record ? $this->_db->record['selection_count'] : 0;		
	}
	
	public function songs_my($limit = 0, $offset = 0, $order = '', $dir = '', $name = '', $album = '', &$next = null) {	
		switch ($order) {
			case 'song':
				$order = 'songs.song_name';
			break;
			
			case 'album':
				$order = 'albums.album_name';
			break;
			
			default:
				$order = 'songs.released';
			break;
		}
		
		$songs = Song::package("songs", sprintf("songs.song_name LIKE '%s' AND albums.album_name LIKE '%s' AND users.id = '%s' 
			GROUP BY songs.id ORDER BY %s %s, songs.released DESC%s", 
			'%' . $name . '%', '%' . $album . '%', $this->_id, $order, $dir,
			($limit ? sprintf(" LIMIT %s, %s", $offset, $limit + 1) : '')));
		
		if ($limit && sizeof($songs) > $limit) {
			$next = true;
			return array_slice($songs, 0, -1);
		}

		return $songs;
	}
    
    public function simple_stats() {
        $qry = sprintf("SELECT COUNT(DISTINCT buyer) AS fans, COUNT(DISTINCT vendor) AS spreaders, COUNT(DISTINCT id) AS downloads
                            FROM song_rights WHERE artist = '%s' GROUP BY artist LIMIT 1;", $this->_id);
        
        if (!$this->_db->query($qry, SQL_INIT)) {
            throw new DatabaseException($this->_db->error);
            return false;
        }        
        
        return $this->_db->record;
    }

}

?>