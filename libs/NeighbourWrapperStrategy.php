<?

class NeighbourWrapperStrategy extends WrapperStrategy {
	
	private $__type = null;
	private $__parent = null;
	
	public function __construct($type = true, $parent = null) {				
		$this->__type = $type;
		$this->__parent = $parent;
	}
	
	public function wrap($row) {
		$db =& new SQL();
		$db->keepalive(true);
		
		if ($this->__type) {
			$qry = sprintf("SELECT genre FROM song_genre UNION 
							SELECT genre FROM artist_genre UNION 
							SELECT genre FROM album_genre 
							GROUP by genre ORDER BY COUNT(genre) DESC");
			
			if (!$db->query($qry, SQL_ALL, new NeighbourWrapperStrategy(false, $row))) {
				throw new DatabaseException($db->error);
				return false;
			}
			
			array_push($db->record, $row['genre']);
			
			return $db->record;
		} else {		
			$qry = sprintf("SELECT count(distinct song) as count, 'song' as type FROM song_genre 
							WHERE song IN (SELECT DISTINCT(song) FROM song_genre WHERE genre = '%s') AND song 
							IN (SELECT DISTINCT(song) FROM song_genre WHERE genre = '%s') UNION 
							SELECT count(distinct artist) as count, 'artist' as type FROM artist_genre 
							WHERE artist IN (SELECT DISTINCT(artist) FROM artist_genre WHERE genre = '%s') 
							AND artist IN (SELECT DISTINCT(artist) FROM artist_genre WHERE genre = '%s') UNION 
							SELECT count(distinct album) as count, 'album' as type FROM album_genre 
							WHERE album IN (SELECT DISTINCT(album) FROM album_genre WHERE genre = '%s') AND album 
							IN (SELECT DISTINCT(album) FROM album_genre WHERE genre = '%s');",
							$this->__parent['genre'], $row['genre'], $this->__parent['genre'], $row['genre'], $this->__parent['genre'], $row['genre']);
			
			if (!$db->query($qry, SQL_ALL)) {
				throw new DatabaseException($db->error);
				return false;
			}
			
			array_push($db->record, $row['genre']);
			
			return ($db->record[0]['count'] + $db->record[1]['count'] + $db->record[2]['count']);		
		}
		
		$db->keepalive(false);
	}
	
}

?>
