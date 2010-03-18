<?php

class Extractor {

	public static function get_tags($limit = 0, $offset = 0) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT genre FROM song_genre UNION 
						SELECT genre FROM artist_genre UNION 
						SELECT genre FROM album_genre 
						GROUP by genre ORDER BY COUNT(genre) DESC%s;",
						$limit ? sprintf(" LIMIT %s, %s", $offset, $limit) : '');
						
		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;
	}

	public static function get_hot_albums($limit = 0, $offset = 0) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT albums.* FROM albums LEFT JOIN songs ON albums.id = songs.album
						LEFT JOIN song_sawce ON song_sawce.song = songs.id
						GROUP BY (albums.id) ORDER BY count(song_sawce.user) DESC%s;",
						$limit ? sprintf(" LIMIT %s, %s", $offset, $limit) : '');
						
		if (!$db->query($qry, SQL_ALL, new ArtistWrapperStrategy($db))) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;		
	}
	
	public static function get_hot_artists($limit = 0, $offset = 0) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT users.* FROM users LEFT JOIN songs ON users.id = songs.artist
						LEFT JOIN song_sawce ON song_sawce.song = songs.id WHERE users.type = 'artist'
						GROUP BY (users.id) ORDER BY count(song_sawce.user) DESC%s;",
						$limit ? sprintf(" LIMIT %s, %s", $offset, $limit) : '');
						
		if (!$db->query($qry, SQL_ALL, new ArtistWrapperStrategy($db))) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;		
	}

	public static function artists_by_tag($tag, $limit = 10, $offset = 0, &$next) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT users.* FROM users 
						LEFT JOIN songs ON users.id = songs.artist
						LEFT JOIN albums ON songs.album = albums.id
						LEFT JOIN artist_genre ON artist_genre.artist = users.id
						LEFT JOIN album_genre ON album_genre.album = albums.id
						LEFT JOIN song_genre ON song_genre.song = songs.id
						LEFT JOIN song_rights ON song_rights.song = songs.id WHERE users.type = 'artist'
						AND (artist_genre.genre = '%s' OR song_genre.genre = '%s' OR album_genre.genre = '%s') 
						GROUP BY (users.id) ORDER BY count(song_rights.buyer) DESC LIMIT %s, %s;",
						$tag, $tag, $tag, $offset, $limit + 1);
						
		if (!$db->query($qry, SQL_ALL, new ArtistWrapperStrategy($db), $limit, $next)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;		
	}

	public static function get_new_artists($limit = 0, $offset = 0) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT users.* FROM users WHERE users.type = 'artist'
						ORDER BY time DESC%s;",
						$limit ? sprintf(" LIMIT %s, %s", $offset, $limit) : '');
						
		if (!$db->query($qry, SQL_ALL, new ArtistWrapperStrategy($db))) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;		
	}

	public static function songs_by_tag($tag, $limit = 0, $offset = 0) {
		$qry = sprintf("song_genre.genre = '%s' OR album_genre.genre = '%s' OR artist_genre.genre = '%s'
						GROUP BY (songs.id) ORDER BY count(song_rights.buyer) DESC", $tag, $tag, $tag);
		$join = "LEFT JOIN song_genre ON song_genre.song = songs.id 
					LEFT JOIN album_genre ON album_genre.album = albums.id
					LEFT JOIN artist_genre ON artist_genre.artist = users.id";
					
		return Song::package('song_rights', $qry, $limit, $offset, $join);	
	}

	public static function get_hot_songs($limit = 0, $offset = 0) {
		return Song::package('song_sawce', 'songs.id > 0 GROUP BY (songs.id) ORDER BY count(song_sawce.user) DESC', $limit, $offset);	
	}
	
	public static function get_spreaders($limit = 0) {
		$db = new SQL();
		
		$qry = sprintf("SELECT usr_1.username AS connector, usr_2.username AS maven, count(DISTINCT sr_1.buyer) AS count 
						FROM users AS usr_1 LEFT JOIN song_rights AS sr_1 ON sr_1.vendor = usr_1.id
						LEFT JOIN song_rights AS sr_2 ON sr_2.buyer = sr_1.vendor AND sr_2.song = sr_1.song
						LEFT JOIN users AS usr_2 ON sr_2.vendor = usr_2.id
						WHERE (usr_2.id IS NOT NULL OR usr_2.id IS NULL) GROUP BY usr_1.id ORDER BY count(DISTINCT sr_1.buyer) DESC
						LIMIT %s;", $limit);

		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}

		return $db->record;
	}
	
	public static function spreaders_by_tag($tag, $limit = 0) {
		$db = new SQL();
		
		$qry = sprintf("SELECT usr_1.username AS connector, usr_2.username AS maven, count(DISTINCT sr_1.buyer) AS count 
						FROM users AS usr_1 LEFT JOIN song_rights AS sr_1 ON sr_1.vendor = usr_1.id
						LEFT JOIN song_rights AS sr_2 ON sr_2.buyer = sr_1.vendor AND sr_2.song = sr_1.song
						LEFT JOIN users AS usr_2 ON sr_2.vendor = usr_2.id 
						LEFT JOIN songs ON sr_1.song = songs.id
						LEFT JOIN song_genre ON song_genre.song = songs.id 
						LEFT JOIN album_genre ON album_genre.album = songs.album
						LEFT JOIN artist_genre ON artist_genre.artist = songs.artist
						WHERE (usr_2.id IS NOT NULL OR usr_2.id IS NULL) AND 
						(song_genre.genre = '%s' OR album_genre.genre = '%s' OR artist_genre.genre = '%s')
						GROUP BY usr_1.id ORDER BY count(DISTINCT sr_1.buyer) DESC 
						LIMIT %s;", $tag, $tag, $tag, $limit);

		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}

		return $db->record;
	}
	
	public static function similar_tags($tag, $limit = 0, $offset = 0) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT genre FROM song_genre WHERE song_genre.song IN (SELECT DISTINCT(song) FROM song_genre WHERE genre = '%s') UNION 
						SELECT genre FROM artist_genre WHERE artist_genre.artist IN (SELECT DISTINCT(artist) FROM artist_genre WHERE genre = '%s') UNION 
						SELECT genre FROM album_genre WHERE album_genre.album IN (SELECT DISTINCT(album) FROM album_genre WHERE genre = '%s')
						GROUP by genre ORDER BY COUNT(genre) DESC%s;",
						$tag, $tag, $tag, $limit ? sprintf(" LIMIT %s, %s", $offset, $limit) : '');	
							
		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return $db->record;
	}
	
	public static function generate_tag_matrix() {
		$db =& new SQL();
		
		$qry = sprintf("SELECT genre FROM song_genre UNION 
						SELECT genre FROM artist_genre UNION 
						SELECT genre FROM album_genre 
						GROUP by genre ORDER BY COUNT(genre) DESC");
						
		if (!$db->query($qry, SQL_ALL, new NeighbourWrapperStrategy(true, null, $db))) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		return Extractor::normalize($db->record);
	}
	
	public static function normalize($matrix) {
		for ($x = 0; $x < sizeof($matrix); $x++) {
			for ($y = 0; $y < sizeof($matrix[$x]) - 1; $y++) {
				if ($x != $y)
					$matrix[$x][$y] /= $matrix[$x][$x];	
			}
			
			$matrix[$x][$x] = 1;
		}
		
		return $matrix;
	}

}

?>
