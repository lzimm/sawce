<?

class EmailValidationStrategy extends ValidationStrategy {
	
	public function validate($value) {
		if (!$this->chain || $this->chain->validate($value)) {
			//if (ereg('^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$',$value)) {
			if (TRUE) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
}

?>
