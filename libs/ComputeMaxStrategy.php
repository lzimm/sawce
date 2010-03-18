<?

class ComputeMaxStrategy extends WrapperStrategy {
	
	private $__max = 0;
	private $__sum = 0;
	private $__col = null;
	
	public function __construct($col) {				
		$this->__col = $col;
	}
	
	public function wrap($row) {	
		if ($row[$this->__col] > $this->__max) {
			$this->__max = $row[$this->__col];
			$this->__sum += $row[$this->__col];
		}
		
		return $row;
	}
	
	public function get() {
		return array('max' => $this->__max, 'sum' => $this->__sum);
	}
	
}

?>
