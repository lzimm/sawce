<?

class SelectValidator extends Validator {

	public function validate() {
		$defaults = array(
							'options' => array(
											array('id' => '', 'name' => '')
										),
							'value' => 'id',
							'label' => 'name',
							'helper' => false,
							'default' => 'None',
                            'tabindex' => 1,
							'max_len' => 128,
							'min_len' => 0
						);
		
		foreach ($defaults as $key => $val) {
			if (!isset($this->params[$key])) {
				$this->params[$key] = $val;
			}
		}

		foreach ($this->params['options'] as $set) {
			if ($this->val == $set[$this->params['value']]) {
				return true;
			}
		}
		
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
			if ($this->params['helper']) {
				if ($this->val == '') {
					$this->val = $this->params['default'];
				}
					
				if ($this->val == $this->params['default']) {
					return true;
				}
				
				if (strlen($this->val) > $this->params['max_len']) {
					return false;
				}
				
				if (strlen($this->val) < $this->params['min_len']) {
					return false;
				}	
			} else {
				$this->val = isset($this->params['options'][0])?$this->params['options'][0][$this->params['value']]:'';
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
		echo "<div class='form_wrapper'>";
		if ($this->params['helper'] == true) {
			printf("<div id=\"%s\" class=\"selectHelper\">",
					(isset($this->params['id'])?$this->params['id']:$this->form_name) . "_wrapper");
					
			printf("<input id=\"%s\" name=\"%s\" value=\"%s\" type=\"text\" class=\"field%s\" onfocus=\"this.select();\" tabindex=\"%s\" />",
					isset($this->params['id'])?$this->params['id']:$this->form_name,
					$this->form_name, $this->val,
					!$this->validates?" error":"",
                    $this->params['tabindex']);
			
			printf("<a href=\"javascript:selectHelperDrop('%s')\"></a>", 
					isset($this->params['id'])?$this->params['id']:$this->form_name);
			
			echo "<div class=\"hints\"><ul>";
			
			foreach ($this->params['options'] as $set) {
				printf("<li onclick=\"setHelper('%s','%s');\" onmouseover=\"this.className='hover';\" onmouseout=\"this.className=''\">%s</li>",
						$set[$this->params['label']], isset($this->params['id'])?$this->params['id']:$this->form_name, $set[$this->params['label']]);
			}
			
			echo "</ul></div>";
			echo "<div class='clear'></div></div>";
		} else {
			printf("<select id=\"%s\" name=\"%s\" tabindex=\"%s\" %s>",
					isset($this->params['id'])?$this->params['id']:$this->form_name, 
					$this->form_name,
                    $this->params['tabindex'],
					isset($this->params['onchange'])?'onchange="'.$this->params['onchange'].'"':'');
			
			foreach ($this->params['options'] as $set) {
				printf("<option value=\"%s\"%s>%s</option>",
						$set[$this->params['value']],
						($this->val == $set[$this->params['value']])?" selected='selected'":'',
						$set[$this->params['label']]);
			}
			
			echo "</select>";
		}
        
        if (isset($this->params['note'])) {
            printf("<div class='form_note'>%s</div>", $this->params['note']);
        }
        
		echo "</div>";	
	}
	
}

?>