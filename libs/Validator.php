<?

abstract class Validator {

	protected $name;
	protected $val;
	protected $validates;
	protected $strategy;	
	protected $form_name;
	
	public $params;

	public function __construct($name, $params = NULL, $strategy = NULL) {
		$this->name = $name;
		$this->strategy = $strategy;
		
        // upon construction, we load the value for the validator; if there's data being
        // posted to the right form, we look for the specified variable and grab it
		if (isset($params['form']) && $params['form']) {
			if (isset($_POST[$params['form']]) && isset($_POST[$params['form']][$name])) {
				$this->val = $_REQUEST[$params['form']][$name];
			} else if ($params && isset($params['preset']) && is_array($params['preset']) && isset($params['preset'][$name])) {
				$this->val = $params['preset'][$name];
			} else if ($params && isset($params['preset'])) {
				$this->val = $params['preset'];
			} else {
				$this->val = '';
			}

			$this->form_name = $params['form'] . '[' . $this->name . ']';	
		} else {
			if (isset($_POST[$name])) {
				$this->val = $_POST[$name];
			} else if ($params && isset($params['preset']) && is_array($params['preset']) && isset($params['preset'][$name])) {
				$this->val = $params['preset'][$name];
			} else if ($params && isset($params['preset'])) {
				$this->val = $params['preset'];
			} else {
				$this->val = '';
			}
			
			$this->form_name = $this->name;
		}
		
		$this->val = trim($this->val);
		
		$this->params = $params;
		
        // finally check if everything validates after we've loaded the value and parameters for the validator
        $this->validates = ($this->xss_safe())?$this->validate():FALSE;
	}
	
	public function revalidate() {
		$this->__construct($this->name, $this->params, $this->strategy);
	}
	
	public function get($default = FALSE) {
		if ($this->validates) {
			return html_entity_decode($this->val);
		} else {
			if ($default) {
				return html_entity_decode($default);
			} else {
				throw new DataException($this->name);
				return false;
			}
		}
	}
	
	public function xss_safe($text = NULL) {
		$unsafe = array();
		$unsafe[] = "!javascript\s*:!is";
		$unsafe[] = "!vbscri?pt\s*:!is";
		$unsafe[] = "!<\s*embed.*swf!is";
		$unsafe[] = "!<[^>]*[^a-z]script\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onabort\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onblur\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onchange\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onmouseout\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onmouseover\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onload\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onreset\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onselect\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onsubmit\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onunload\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onerror\s*=!is";
		$unsafe[] = "!<[^>]*[^a-z]onclick\s*=!is";
		$unsafe[] = "/((\\%3C)|(&lt;)|<)script[^\\0]*((\\%3E)|(&gt;)|>)/";
		
		$text = ($text)?$text:$this->val;
		
        // if we're scrubbing the value caught by the form element, we make sure to strip out all script tags
        // and replace them with blanks, then return false, rather than just return false as below
		if ($text == $this->val) {
			if (preg_match($unsafe[sizeof($unsafe) - 1], $text, $matches)) {
				$this->val = $text = preg_replace("/((\\%3C)|(&lt;)|<)script[^\\0]*((\\%3E)|(&gt;)|>)/", '', $text);
				$this->val = $text = str_replace("</script>", '', $text);
				
				return false;
			}
		}
		
		$text = htmlentities($text);
		
		// now we just check with all the scrubbers
        foreach($unsafe as $match) {		
			if (preg_match($match, $text, $matches)) {				
				return false;
			}
		}
		
		return true;
	}
	
	public function invalidate() {
		$this->validates = FALSE;
	}
	
	public abstract function validate();
	public abstract function build();
	
}

?>
