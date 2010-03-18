<?php

class Song {

	public $_id = NULL;
	public $_song_name = NULL;
	public $_artist = NULL;
	public $_artist_user = NULL;
	public $_display_name = NULL;
	public $_type = NULL;
    public $_open_price = NULL;
	public $_price = NULL;
	public $_commission = NULL;
	public $_sample_start = NULL;
	public $_sample_end = NULL;
	public $_album = NULL;
	public $_album_name = NULL;
	public $_album_art = NULL;
	public $_artist_status = NULL;
    public $_license = NULL;
	public $_released = NULL;
    public $_active = NULL;
	public $_db = NULL;
	
	public $_song_permission = NULL;
	
	public $_secret = NULL;
	
	public function __construct($params) {
        foreach ($this as $key => $val) {
            if (isset($params[substr($key, 1)])) {
                $this->$key = $params[substr($key, 1)];
            }
        }
        
        $this->_db =& new SQL(); 
		            
		$this->_secret = $this->_id . '-' . substr(md5($this->_released), 0, 10);
	}	
	
	public static function create($path, $artist, $song_name, $album, $released = NULL, $type = 'public', $price = 0.99, $commission = 0.5, $sample_start = 0, $sample_end = 0, $id3_name = false) {
		if (!$released) {
			$released = date("Y-m-d H:i:s", time());
		}
		
		if (Album::find($album, $artist)) {
			$db =& new SQL();
		
			$qry = sprintf("INSERT INTO songs (artist, song_name, type, price, commission, sample_start, sample_end, album, released) 
				VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
				$artist, $song_name, $type, $price, $commission, $sample_start, $sample_end, $album, $released);
			
			if ($db->query($qry)) {
				$id = $db->get_id();
				$song = Song::find($id);
				
				if (!file_exists($GLOBALS['cfg']['basedir'] . 'songs/' . $artist . '/')) {
					mkdir ($GLOBALS['cfg']['basedir'] . 'songs/' . $artist . '/');
				}
								
				if (!rename($GLOBALS['cfg']['basedir'] . $path, $GLOBALS['cfg']['basedir'] . 'songs/' . $artist . '/' . $id . '.mp3')) {
					$song->delete();
					
					throw new FileException(ERR_FILE_MOVE);
					
					return false;
				}
				
				$song->_verify_mp3($id3_name); 
				$song->_generate_preview();
				$song->_build();
				$song->grant_rights(0,0,0);
								
				return $song;
			} else {
				throw new DatabaseException($db->error);
				return false;
			}
		} else {
			throw new PermissionException(ERR_ARTIST_PERMISSION);
			return false;
		}
	}
	
	private function _verify_mp3($id3_name = false) {
		require_once('libs/getid3/getid3.php');
		$getID3 = new getID3();
		$getID3->setOption(array('encoding' => 'UTF-8'));
		
		$info = $getID3->analyze($GLOBALS['cfg']['basedir'] . 'songs/' . $this->_artist . '/' . $this->_id . '.mp3');
		if ($info['fileformat'] == 'mp3') {     
		    if (($id3_genres = $this->__verify_id3($info, $id3_name)) !== FALSE) {
                if (is_array($id3_genres)) {
					foreach($id3_genres as $genre) {
                    	$this->add_genre(mysql_escape_string($genre));
                	}
				}
                
                return true;
            } else {
                $this->delete();
                throw new FileException(ERR_INVALID_ID3);
                return false;
            }
		} else {
			$this->delete();
			throw new FileException(ERR_NOT_MP3);
			return false;
		}
	}
    
    private function __verify_id3($info, $id3_name = false) {     
        if (isset($info['tags']['id3v1']) && $info['tags']['id3v1']['artist'] && $info['tags']['id3v1']['title']) {
            if ((shared_content($info['tags']['id3v1']['artist'][0], $this->_display_name) || true) && 
                (shared_content($info['tags']['id3v1']['title'][0], $this->_song_name) || $id3_name)) {
               
				if (!shared_content($info['tags']['id3v1']['title'][0], $this->_song_name)) {
					$this->edit(mysql_escape_string($info['tags']['id3v1']['title'][0]), $this->_album, 
								$this->_price, $this->_commission, 
								$this->_sample_start, $this->_sample_end, $this->_type);
				}
				
                return $info['tags']['id3v1']['genre'];
            }            
        } else if (isset($info['tags']['id3v2']) && $info['tags']['id3v2']['artist'] && $info['tags']['id3v2']['title']) {      
            if ((shared_content($info['tags']['id3v2']['artist'][0], $this->_display_name) || true) && 
                shared_content($info['tags']['id3v2']['title'][0], $this->_song_name) || $id3_name) {

				if (!shared_content($info['tags']['id3v2']['title'][0], $this->_song_name)) {
					$this->edit(mysql_escape_string($info['tags']['id3v2']['title'][0]), $this->_album, 
								$this->_price, $this->_commission, 
								$this->_sample_start, $this->_sample_end, $this->_type);
				}
                    
                return $info['tags']['id3v1']['content_type']; 
            }
        }

        return false;
    }
	
	private function _generate_preview() {
		$in = $GLOBALS['cfg']['basedir'] . 'songs/' . $this->_artist . '/' . $this->_id . '.mp3';
		$out = $GLOBALS['cfg']['basedir'] . 'songs/' . $this->_artist . '/' . $this->_id . '-sample.mp3';
		
		$in_size = @filesize($in);
		
		$in_file = @fopen($in, "r");
		$out_file = @fopen($out, "w");

		while (@ftell($in_file) <= ($in_size/2)) {
    		@fwrite($out_file, @fread($in_file, 1024));
		}
		
		@fclose($in_file);
		@fclose($out_file);
	}
	
	private function _build() {
		$s3 = new S3();
		if (!$s3->putObject('sawcesongs', 
							$this->_artist . '/' . $this->_secret . '.mp3', 
							$GLOBALS['cfg']['basedir'] . 'songs/' . $this->_artist . '/' . $this->_id . '.mp3', 
							true)) {
			$this->delete();
		
			throw new FileException(ERR_FILE_MOVE);
		
			return false;
		}

		if (!$s3->putObject('sawcesongs', 
							$this->_artist . '/' . $this->_id . '.mp3', 
							$GLOBALS['cfg']['basedir'] . 'songs/' . $this->_artist . '/' . $this->_id . '-sample.mp3', 
							true)) {
			$this->delete();
		
			throw new FileException(ERR_FILE_MOVE);
		
			return false;
		}
		
		return true;
	}
	
	public static function find($id, $artist = NULL) {
		$songs = Song::package("songs", 
					sprintf("songs.id = '%s'", $id));
		
		if ($songs) {
			if ($artist && ($songs[0]->_artist != $artist)) {
				throw new PermissionException(ERR_ARTIST_PERMISSION);
				return false;
			}
			
			return $songs[0];
		} else {
			return false;
		}
	}
	
	public function edit($song_name, $album, $price = 0.99, $commission = 0.5, $sample_start = 0, $sample_end = 0, $type = 'public') {
		$qry = sprintf("UPDATE songs SET 
			song_name = '%s', album = '%s', price = '%s', commission = '%s', sample_start = '%s',
			sample_end = '%s', type = '%s' WHERE id = '%s';",
			$song_name, $album, $price, $commission, $sample_start, $sample_end, $type, $this->_id);
	
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function delete() {
		$qry = sprintf("DELETE FROM songs WHERE id = '%s' LIMIT 1;", $this->_id);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}

	public static function package($table, $where, $limit = 0, $offset = 0, $join = '', $select = 'songs', $force = false, $check = FALSE) {
		$db =& new SQL();
		$qry = sprintf(
			"SELECT %s.*, users.display_name, users.username AS artist_user, users.status AS artist_status,
			albums.id AS album, albums.album_name, albums.art AS album_art%s
			FROM %s %s 
			INNER JOIN albums ON %s.album = albums.id
			INNER JOIN users ON %s.artist = users.id %s%s
			WHERE %s%s;", 
				$select, 
				$check ? ', check_rights.id AS song_permission' : '',
				$table, 
				!$force ? $table != 'songs'?sprintf("JOIN songs ON %s.song = songs.id", $table):'':'',
				$select, $select, $join, 
				$check ? sprintf(" LEFT JOIN song_rights as check_rights ON (check_rights.song = '%s' AND check_rights.buyer = '%s')",
					($table != 'songs'?sprintf("%s.song", $table) : 'songs.id'), $check):'',
				$where,
				$limit ? sprintf(" LIMIT %s, %s", $offset, $limit) : '');
		
		if (!$db->query($qry, SQL_ALL, new SongWrapperStrategy())) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;
	}
	
	public static function play($song, $user) {
		$db =& new SQL();
		$qry = sprintf("INSERT INTO song_plays (song, user) VALUES ('%s', '%s');", $song, $user);
		if (!$db->query($qry)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return true;	
	}
	
	public function check_rights($user) {
		if ($user == $this->_artist) {
			return true;
		}
		
		$qry = sprintf("SELECT * FROM song_rights WHERE song = '%s' AND buyer = '%s' LIMIT 1;", $this->_id, $user);
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
	
	public function grant_rights($user, $vendor = NULL, $ip = NULL) {
		if (!$vendor) {
			$vendor = $this->_artist;
		}
		
		$qry = sprintf("INSERT INTO song_rights (song, buyer, artist, vendor, buy_entry, sale_entry, ref_entry, ip, granted) VALUES ('%s', '%s', '%s', '%s', '0', '0', '0', '%s', '1');", 
			$this->_id, $user, $this->_artist, $vendor, $ip);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;	
	}

	public function set_album($album) {
		$qry = sprintf("UPDATE songs SET album = '%s' WHERE id = '%s';", $this->_id, $album);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}

	public function get_genres($artist = true) {
		$qry = sprintf("SELECT * FROM song_genre WHERE song = '%s' AND user %s GROUP BY genre;", 
			$this->_id, ($artist ? '= ' : '!= ') . $this->_artist);
		
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$album_genres = Album::find($this->_album)->get_genres(true, $artist);
		
		$genres = array();
		foreach($this->_db->record as $genre) {
			$genres[] = $genre['genre'];
		}
		
		return array($album_genres, $genres);
	}
	
	public function add_genre($genre, $user = null) {
		if (!$user) {
			$user = $this->_artist;
		}
		
		$qry = sprintf("INSERT INTO song_genre (song, genre, user, artist) VALUES ('%s', '%s', '%s', '%s');", 
			$this->_id, $genre, $user, $this->_artist);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function remove_genre($genre, $artist = true) {
		$qry = sprintf("DELETE FROM song_genre WHERE song = '%s' AND genre LIKE '%s' AND user %s;", 
			$this->_id, $genre, ($artist ? '= ' : '!= ') . $this->_artist);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}

	public function get_similar_songs($limit = 5) {
		$qry = sprintf("my_song.id = '%s' AND their_songs.id != '%s' GROUP BY their_songs.id ORDER BY count(*) DESC", 
						$this->_id, $this->_id);
		$join = "LEFT JOIN song_rights AS their_rights ON their_rights.song = their_songs.id
				LEFT JOIN song_rights AS my_rights ON my_rights.buyer = their_rights.buyer
				LEFT JOIN songs AS my_song ON my_rights.song = my_song.id";
				
		return Song::package('songs AS their_songs', $qry, $limit, 0, $join, 'their_songs', true);	
	}

	public function get_spreaders($limit = 10) {
		$qry = sprintf("SELECT usr_1.username AS connector, usr_2.username AS maven, count(DISTINCT sr_1.buyer) AS count 
						FROM users AS usr_1 LEFT JOIN song_rights AS sr_1 ON sr_1.vendor = usr_1.id
						LEFT JOIN song_rights AS sr_2 ON sr_2.buyer = sr_1.vendor AND sr_2.song = sr_1.song
						LEFT JOIN users AS usr_2 ON sr_2.vendor = usr_2.id
						WHERE sr_1.song = '%s' AND usr_2.id IS NOT NULL GROUP BY usr_1.id ORDER BY count(DISTINCT sr_1.buyer) DESC 
						LIMIT %s;", $this->_id, $limit);
						
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return $this->_db->record;		
	}
	
}

?>
