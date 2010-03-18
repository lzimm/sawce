<?

class SongWrapperStrategy extends WrapperStrategy {
	
	public function wrap($row) {	
		return new Song($row);
	}
	
}

?>
