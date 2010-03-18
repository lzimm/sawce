<?

class CheckValidator extends Validator {

	public function validate() {
		$defaults = array(
							'required' => true,
							'default' => '',
							'lookfor' => 'submit'
						);
		
		foreach ($defaults as $key => $val) {
			if (!isset($this->params[$key])) {
				$this->params[$key] = $val;
			}
		}
			
		$form_set = FALSE;
		if (isset($this->params['form']) && $this->params['form']) {
			if (isset($_POST[$this->params['form']])) {
				$form_set = TRUE;
			}
		} else {
			if ($_POST && isset($_POST[$this->params['lookfor']])) {
				$form_set = TRUE;
			}
		}
		
		if ($form_set) {
			$this->val = mysql_escape_string($this->val);
	
			if ($this->val == '') {
				if ($this->params['required']) {
					return false;
				}
				
				$this->val = $this->params['default'];
			}
			
			if ($this->val == $this->params['default']) {
				return true;
			}
			
			return true;
		} else {
			$this->val = ($this->val == '')?$this->params['default']:$this->val;
			return true;
		}
	}
	
	public function build() {
		printf("<div class=\"check%s%s\"><input id=\"%s\" name=\"%s\" type=\"checkbox\" %s /></div>",
			!$this->validates?' check_error':'',
			$this->params['required']?' check_required':'',
			$this->form_name, $this->form_name, 
			$this->val?'checked="yes"':''
			);
	}
	
}

?>