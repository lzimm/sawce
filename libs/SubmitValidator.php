<?

class SubmitValidator extends Validator {

	public function validate() {
		$defaults = array(	
							'label' => 'Submit',
							'onClick' => ''
						);
		
        // load default params
		foreach ($defaults as $key => $val) {
			if (!isset($this->params[$key])) {
				$this->params[$key] = $val;
			}
		}

		return true;
	}
	
	public function build($action = null) {
        // this is kind of messy, but basically we automatically call the form submission javascript whenever a submit is hit
        // and if we need another action after that, we wrap it up inside the submission script, which checks for swf file uploads
        // and also scans for all required fields
		printf("<input name=\"session_id\" type=\"hidden\" value=\"%s\" />", session_id());
		printf("<button id=\"%s\" name=\"%s\" type=\"submit\"%s value=\"%s\"%s><div><span>%s</span></div></button>",
			isset($this->params['id'])?$this->params['id']:$this->form_name, $this->form_name, 
			!isset($this->params['nojs'])?sprintf(" onClick=\"return swfu_try(this, function(){%s});\"",
				$action?$action:$this->params['onClick']?$this->params['onClick']:'return true;'):'', 
			$this->params['label'], 
            isset($this->params['tabindex'])?' tabindex="'.$this->params['tabindex'].'"':'',
            $this->params['label']);
	}
	
}

?>