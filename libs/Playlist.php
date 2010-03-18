<?php

class Playlist {

	public $_id = NULL;
	public $_user = NULL;
	public $_title = NULL;
	public $_db = NULL;
	
	private $_songs = NULL;
	
	private function __construct($params) {
        foreach ($this as $key => $val) {
            if (isset($params[substr($key, 1)])) {
                $this->$key = $params[substr($key, 1)];
            }
        }

        $this->_db =& new SQL();
	}
	
	public static function create($user, $title) {
		$db =& new SQL();

		$qry = sprintf("INSERT INTO playlists (user, title) 
			VALUES ('%s', '%s');",
			$user, $title);
		
		if ($db->query($qry)) {
			$id = $db->get_id();
			return Playlist::find($id);
		} else {
			throw new DatabaseException($db->error);
			return false;
		}
	}
	
	public static function find($id, $user = NULL) {
		$db =& new SQL();
		$qry = sprintf("SELECT * FROM playlists WHERE id = '%s' LIMIT 1;", $id);
		
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			if ($user && ($user != $db->record['user'])) {
				throw new PermissionException(ERR_USER_PERMISSION);
				return false;			
			}
							
			return new Playlist($db->record);
		} else {
			return false;
		}	
	}
	
	public static function by_user($user, $objects = TRUE) {
		$db =& new SQL();
        
		$qry = sprintf("SELECT * FROM playlists WHERE user = '%s';", $user);
		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($objects) {
			$playlists = array();
			
			foreach ($db->record as $playlist) {
				$playlists[] = new Playlist($playlist);
			}
		} else {
			$playlists = $db->record;
		}
		
		return $playlists;
	}
	
	public static function get_user($id) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT users.* FROM users LEFT JOIN playlists ON users.id = playlists.user WHERE playlists.id = '%s' LIMIT 1;", $id);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {	
			return new User($db->record);
		} else {
			return false;
		}
	}

	public function delete() {			
		$qry = sprintf("DELETE FROM playlists WHERE id = '%s' LIMIT 1;", $this->_id);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;		
	}

	public function edit($title) {		
		$qry = sprintf("UPDATE playlists SET title = '%s' WHERE id = '%s';", 
			$title);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_title = $title;
		
		return true;
	}
	
	public function get_songs() {
		if (!isset($this->_songs)) {
			$this->_songs = Song::package('playlist_songs', 
				sprintf("playlist_songs.playlist = '%s' GROUP BY songs.id ORDER BY songs.released DESC, songs.added DESC", 
					$this->_id));
		}
		
		return $this->_songs;
	}

	public function add_song($song) {
		$song = Song::find($song);

		if ($song->check_rights($this->_user)) {
			$qry = sprintf("INSERT INTO playlist_songs (playlist, song) VALUES ('%s', '%s');", $this->_id, $song);
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

	public function remove_song($song) {
		$qry = sprintf("DELETE FROM playlist_songs WHERE playlist = '%s' AND song = '%s';",
							$this->_id, $song);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
}

?>
