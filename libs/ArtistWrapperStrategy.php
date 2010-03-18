<?

class ArtistWrapperStrategy extends WrapperStrategy {
	
	public function wrap($row) {	
		return new Artist($row);
	}
	
}

?>
