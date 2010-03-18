<?

class FanWrapperStrategy extends WrapperStrategy {
	
	public function wrap($row) {	
		$my_row = array(
			'user' => new User($row), 
			'count' => $row['count'],
			'spread' => $row['spread']);
			
		return $my_row;
	}
	
}

?>
