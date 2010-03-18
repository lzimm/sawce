<?

class TextValidator extends Validator {

	public function validate() {
		$defaults = array(
							'required' => true,
							'default' => '',
							'max_len' => 128,
							'min_len' => 0,
							'currency' => false,
                            'tabindex' => 1,
							'regex' => '/^[a-zA-Z0-9_ -@\\.\']+$/'
						);
		
		if ($this->params && isset($this->params['time']) && $this->params['time']) {
			$defaults['default'] = '0000-00-00 00:00:00';
		}
		
        // load default params
		foreach ($defaults as $key => $val) {
			if (!isset($this->params[$key])) {
				$this->params[$key] = $val;
			}
		}
		
        // check if we're supposed to be looking for this form element or not by checking
        // if the right form is being posted
		$form_set = FALSE;
		if (isset($this->params['form']) && $this->params['form']) {
			if (isset($_POST[$this->params['form']])) {
				$form_set = TRUE;
			}
		} else {
			if ($_POST && isset($_POST[$this->name])) {
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
			
			if ((!isset($this->params['type']) || $this->params['type'] != 'text') && strlen($this->val) > $this->params['max_len']) {
				return false;
			}
			
			if ($this->params['min_len'] && (strlen($this->val) < $this->params['min_len'])) {
				return false;
			}
			
			if (isset($this->params['time']) && $this->params['time']) {	
				if ($this->val != $this->params['default'] && strtotime($this->val) > 1) {
					$this->val = date("Y-m-d H:i:s", strtotime($this->val));
				} else {
					$this->val = $this->params['default'];
				}
			} else if (!ereg($this->params['regex'], $this->val) || ($this->val != '')) {
				//return false;
			}
			
			if ($this->params['currency']) {
				$this->val = sprintf("%1\$.2f", $this->val);
			}
			
			if (isset($this->params['maxval']) && (($this->val - $this->params['maxval']) > 7E-3)) {
				return false;
			}

			if ($this->strategy) {
				return $this->strategy->validate($this->val);
			}

			return true;
		} else {
			$this->val = ($this->val == '')?$this->params['default']:$this->val;
			return true;
		}
	}
	
	public function build() {		
		$value = (isset($this->params['time']) && $this->params['time'])?
					($this->val == '0000-00-00 00:00:00')?'n/a':date("F d, Y", strtotime($this->val)):
					$this->val;
		
		$value = stripslashes($value);
		$value = htmlentities($value);

		if (isset($this->params['flush'])) {
			$value = '';
		}
		
		echo "<div class='form_wrapper'>";
		if (isset($this->params['type']) && $this->params['type'] == 'text') {
			printf("<textarea id=\"%s\" name=\"%s\" tabindex=\"%s\" class=\"%s%s\">%s</textarea>",
				$this->form_name, $this->form_name, $this->params['tabindex'],
				!$this->validates?'error':'', 
				$this->params['required']?' required':'',
				$value);			
		} else {
			printf("<input id=\"%s\" name=\"%s\" tabindex=\"%s\" type=\"%s\" class=\"field%s%s\" value=\"%s\" />",
				$this->form_name, $this->form_name, $this->params['tabindex'],
				isset($this->params['password'])?'password':'text',
				!$this->validates?' error':'', 
                $this->params['required']?' required':'',
				isset($this->params['password'])?'':$value);
		}
        
        if (isset($this->params['note'])) {
            printf("<div class='form_note'>%s</div>", $this->params['note']);
        }

		echo "</div>";
	}
	
}

?>