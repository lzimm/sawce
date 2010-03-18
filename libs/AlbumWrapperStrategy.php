<?

class AlbumWrapperStrategy extends WrapperStrategy {
	
	public function wrap($row) {	
		return new Album($row);
	}
	
}

?>
