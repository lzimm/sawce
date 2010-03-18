<?php

class Album {

	public $_id = NULL;
	public $_artist = NULL;
	public $_artist_user = NULL;
	public $_display_name = NULL;
	public $_album_name = NULL;
	public $_released = NULL;
	public $_art = NULL;
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
	
	public static function create($artist, $album_name, $released = NULL, $img = NULL) {
		if (!$released) {
			$released = date("Y-m-d H:i:s", time());
		}

		$art_ext = 'none';
		
		if ($img) {
			$imglower = strtolower($img);
			$fileparts = array(substr($imglower, 0, strrpos($img, '.')),
						strtolower(substr($img, strrpos($img, '.') + 1)));
			
			if (!file_exists($GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/')) {
				mkdir ($GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/');
			}
						
			if (!rename($GLOBALS['cfg']['basedir'] . $img, $GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/'. $this->_id . '.' . $fileparts[1])) {
				throw new FileException(ERR_FILE_MOVE);
			} else {
				$art_ext = ($fileparts[1]=='jpeg')?'jpg':$fileparts[1];
				$this->_art = $art_ext;
				
				$this->__build_art_previews();
			}
		}

		$db =& new SQL();

		$qry = sprintf("INSERT INTO albums (artist, album_name, released, art) 
			VALUES ('%s', '%s', '%s', '%s');",
			$artist, $album_name, $released, $art_ext);
		
		if ($db->query($qry)) {
			$id = $db->get_id();
			return Album::find($id);
		} else {
			throw new DatabaseException($db->error);
			return false;
		}
	}
	
	public static function find($id, $artist = NULL) {
		$db =& new SQL();
		$qry = sprintf("SELECT albums.*, users.display_name, users.username AS artist_user
			FROM albums INNER JOIN users ON albums.artist = users.id
			WHERE albums.id = '%s' LIMIT 1;", $id);
		
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {
			$album = $db->record;
			
			if ($artist && ($album['artist'] != $artist)) {
				throw new PermissionException(ERR_ARTIST_PERMISSION);
				return false;
			}
			
			return new Album($album);
		} else {
			return false;
		}	
	}

	public static function search($string, $offset = 0, $prefix = false, &$next) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT albums.*, users.display_name, users.username AS artist_user
			FROM albums INNER JOIN users ON albums.artist = users.id WHERE album_name LIKE '%s' ORDER BY album_name LIMIT %s, 26;",
			$prefix?$string.'%':'%'.$string.'%', $offset);
			
		$db->query($qry, SQL_ALL, new AlbumWrapperStrategy($db), 25, $next);
		
		return $db->record;
	}
	
	public static function by_artist($artist, $objects = TRUE, $limit = 0) {
		$db =& new SQL();
		$qry = sprintf("SELECT albums.*, users.display_name, users.username AS artist_user FROM albums JOIN users
						ON albums.artist = users.id WHERE albums.artist = '%s'%s;", $artist, $limit ? sprintf(" LIMIT %s", $limit) : '');
		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($objects) {
			$albums = array();
			
			foreach ($db->record as $album) {
				$albums[] = new Album($album);
			}
		} else {
			$albums = $db->record;
		}
		
		return $albums;
	}
	
	public static function get_artist($id) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT users.* FROM users LEFT JOIN albums ON users.id = albums.artist WHERE albums.id = '%s' LIMIT 1;", $id);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		if ($db->record) {	
			return new Artist($db->record);
		} else {
			return false;
		}
	}

	public static function get_artist_default($artist) {
		$db =& new SQL();
		$qry = sprintf("SELECT albums.*, users.display_name, users.username AS artist_user FROM albums JOIN users
						ON albums.artist = users.id WHERE albums.artist = '%s'
						ORDER BY id ASC LIMIT 1;", $artist);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}		
		
		if ($album = $db->record) {
			return new Album($album);
		} else {
			throw new StructureException(ERR_STRUCT_NO_DEF_ALBUM);
			return false;
		}
	}
	
	public function delete() {
		$default = Album::get_artist_default($this->_artist);
		
		if ($this->_id != $default->_id) {
			$qry = sprintf("UPDATE songs SET album = '%s' WHERE album = '%s';", $default->_id, $this->_id);
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			$qry = sprintf("DELETE FROM albums WHERE id = '%s' LIMIT 1;", $this->_id);
			if (!$this->_db->query($qry)) {
				throw new DatabaseException($this->_db->error);
				return false;
			}
			
			return true;		
		} else {
			throw new PermissionException(ERR_ALBUM_PERMISSION);
			return false;
		}
	}

	public function edit($name, $img = NULL) {
		$art_ext = $this->_art;
		
		if ($img) {
			$imglower = strtolower($img);
			$fileparts = array(substr($img, 0, strrpos($imglower, '.')),
						strtolower(substr($img, strrpos($img, '.') + 1)));
			
			if (!file_exists($GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/')) {
				mkdir ($GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/');
			}
			
			if (!rename($GLOBALS['cfg']['basedir'] . $img, $GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/'. $this->_id . '.' . $fileparts[1])) {
				throw new FileException(ERR_FILE_MOVE);
			} else {
				$art_ext = ($fileparts[1]=='jpeg')?'jpg':$fileparts[1];
				$this->_art = $art_ext;
				$this->__build_art_previews();
			}
		}
		
		$qry = sprintf("UPDATE albums SET album_name = '%s', art = '%s' WHERE id = '%s';", 
			$name, $art_ext, $this->_id);
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_album_name = $name;
		$this->_art = $art_ext;
		
		return true;
	}
	
	public function get_songs() {
		if (!isset($this->_songs)) {
			$this->_songs = Song::package('songs', 
				sprintf("album = '%s' GROUP BY songs.id ORDER BY songs.released DESC, songs.added DESC", 
					$this->_id));
		}
		
		return $this->_songs;
	}

	public function add_song($song) {
		$song = Song::find($song);
		$song->set_album($this->_id);
	}
	
	public function get_genres($concat = false, $artist = true) {
		$qry = sprintf("SELECT * FROM album_genre WHERE album = '%s' AND user %s GROUP BY genre;", 
			$this->_id, ($artist ? '= ' : '!= ') . $this->_artist);
		
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$artist_genres = Artist::find_by_id($this->_artist)->get_genres($artist);
		$genres = $concat?$artist_genres:array();
		foreach($this->_db->record as $genre) {
			$genres[] = $genre['genre'];
		}
		
		if ($concat) {
			return $genres;
		} else {
			return array($artist_genres, $genres);
		}
	}
	
	public function add_genre($genre, $user = null) {
		if (!$user) {
			$user = $this->_artist;
		}
		
		$qry = sprintf("INSERT INTO album_genre (album, genre, user, artist) VALUES ('%s', '%s', '%s', '%s');", 
			$this->_id, $genre, $user, $this->_artist);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public function remove_genre($genre, $artist = true) {
		$qry = sprintf("DELETE FROM album_genre WHERE album = '%s' AND genre LIKE '%s' AND user %s;", 
			$this->_id, $genre, ($artist ? '= ' : '!= ') . $this->_artist);
			
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}
	
	public static function build_custom_art_preview($artist, $album, $type, $size) {
		$original = $GLOBALS['cfg']['basedir'] . 'art/' . $artist . '/'. $album . '.' . $type;
		$new = $GLOBALS['cfg']['basedir'] . 'art/' . $artist . '/'. $album . '-' . $size . '.' . $type;
		
		$override = TRUE;
		
		if (!file_exists($new) || $override) {
			list($width, $height) = getimagesize($original);

			$i_func = '';
			$o_func = '';
			
			switch($type) {
				case 'gif':
					$i_func = 'imagecreatefromgif';
					$o_func = 'imagegif';
				break;
				
				case 'jpg':
				case 'jpeg':
					$i_func = 'imagecreatefromjpeg';
					$o_func = 'imagejpeg';
				break;
				
				case 'png':
					$i_func = 'imagecreatefrompng';
					$o_func = 'imagepng';
				break;
				
				default:
					throw new FileException(ERR_IMG_TYPE);
					return false;
				break;
			}
		
			$img = imagecreatetruecolor($size, $size);
			$img_tmp = $i_func($original);
			
			imagesavealpha($img, true);
    		$trans_colour = imagecolorallocatealpha($img, 255, 255, 255, 0);
    		imagefill($img, 0, 0, $trans_colour);
			imagecopyresampled($img, $img_tmp, 0, 0, 0 ,0, $size, $size, $width, $height);
			
			$o_func($img, $new);	
		}
		
		$s3 = new S3();
		if (!$s3->putObject('sawceart', $artist . '/' . $album . '-' . $size . '.' . $type, $new, true)) {			
			throw new FileException(ERR_FILE_MOVE);
		}	
	}

	private function __build_art_previews() {
		$this->__delete_art_previews();
		Album::build_custom_art_preview($this->_artist, $this->_id, $this->_art, 50);
		Album::build_custom_art_preview($this->_artist, $this->_id, $this->_art, 100);
		Album::build_custom_art_preview($this->_artist, $this->_id, $this->_art, 200);		
	}
	
	private function __delete_art_previews() {
		$files = glob($GLOBALS['cfg']['basedir'] . 'art/' . $this->_artist . '/'. $this->_id . '-' . '*');
		foreach($files as $file) {
			unlink($file);
		}
	}

}

?>
