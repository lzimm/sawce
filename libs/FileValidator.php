<?

class FileValidator extends Validator {

	public function validate() {
		$defaults = array(
							'required' => true,
							'max_size' => 50000000,
							'path' => 'files/',
							'bucket' => '',
							'swf_uploader' => '/swf_uploader.php'
						);
		
        // load default params
		foreach ($defaults as $key => $val) {
			if (!isset($this->params[$key])) {
				$this->params[$key] = $val;
			}
		}
		
        // now we check if the right form is being posted as well as if the file is being posted too
		$form_set = FALSE;
		$is_fileset = FALSE;
		$file = NULL;
        
        // first check if we're getting the file through the swf uploader or not
		if (isset($_POST[$this->_strip($this->form_name, false).'_swfed']) && ($_POST[$this->_strip($this->form_name, false).'_swfed'])) {
			$tmp = unserialize(urldecode($_POST[$this->_strip($this->form_name, false).'_swfed']));
			
            // if there's an swf upload, it passes a serialized array of the file info and a secret hash through the _swfed variable in the above
            // then we just check it all out
			if (($tmp['secret'] == md5($GLOBALS['cfg']['secret'] . $tmp['filepath'])) && file_exists($tmp['filepath'])) {
				$this->val = $tmp['filepath'];
				return true;
			}
            
            if ($this->params['required']) {
                return true;
            }
		}
		
        // if the right form is being posted, we go look for our file
		if (isset($this->params['form']) && $this->params['form']) {
			if (isset($_FILES[$this->params['form']]) && $_FILES[$this->params['form']]['size'][$this->name]) {
				$is_fileset = TRUE;
				$tmp = $_FILES[$this->params['form']];
				
				$file = array();
				foreach ($tmp as $key => $value) {
					$file[$key] = $value[$this->name];
				}
			}

			if (isset($_POST[$this->params['form']])) {
				$form_set = TRUE;
			}
		} else {
			if (isset($_FILES[$this->name]) && $_FILES[$this->name]['size']) {
				$is_fileset = TRUE;
				$file = $_FILES[$this->name];
			}

			if ($_POST) {
				$form_set = TRUE;
			}
		}
		
        // if we've got out form and file, move it around so we can play with it with whatever is going to play with it
		if ($form_set) {
			if ($is_fileset) {
				if (!file_exists($GLOBALS['cfg']['basedir'] . $this->params['path'])) {
					mkdir ($GLOBALS['cfg']['basedir'] . $this->params['path']);
				}
	
				if (!file_exists($GLOBALS['cfg']['basedir'] . $this->params['path'] . $this->params['bucket'])) {
					mkdir ($GLOBALS['cfg']['basedir'] . $this->params['path'] . $this->params['bucket']);
				}				

				if (isset($this->params['dynamic'])) {
					if (isset($this->params['dynamic']['set'])) {
						$this->params['bucket'] .= '/' . $this->params['dynamic']['set']->get();
					}
				}
				
				if (!file_exists($GLOBALS['cfg']['basedir'] . $this->params['path'] . $this->params['bucket'])) {
					mkdir ($GLOBALS['cfg']['basedir'] . $this->params['path'] . $this->params['bucket']);
				}

				$rootpath = $this->params['path'] . $this->params['bucket'] . '/';
		
				if (!file_exists($GLOBALS['cfg']['basedir'] . $rootpath . $file['name'])) {
					$filepath = $rootpath . $file['name'];
				} else {
					$i = 1;
					
					$fileparts = array(substr($file['name'], 0, strrpos($file['name'], '.')),
						substr($file['name'], strrpos($file['name'], '.')));
					
					while (file_exists($GLOBALS['cfg']['basedir'] . $rootpath . $fileparts[0] . $i . $fileparts[1])) {
						$i++;
					}
					$filepath = $rootpath . $fileparts[0] . $i . $fileparts[1];
				}
				
				if (!move_uploaded_file($file['tmp_name'], $GLOBALS['cfg']['basedir'] . $filepath)) {
					return false;
				} else {
					$this->val = $filepath;
					return true;
				}
			} else {
				$this->val = '';
			}
			
			if ($this->params['required'] && !$this->val) {
				return false;
			}
			
			return true;
		} else {
			return true;
		}
	}
	
	public function build() {	
		echo "<div>";	
		printf("<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"%s\" />", $this->params['max_size']);
		
		if ($this->params['required']) {
			echo '<div class="file_required">';
		}
		
        // we try to load the swf uploader by default, but we allow depreciation
		printf('<div class="file_input swf_file_input" id="div_%1$s" style="display: none;">
					<input type="hidden" name="%1$s_swfed" value="" />
					<a href="javascript:swfu_%1$s.browse();" class="upload_browse"><span>Browse</span></a>
					<a href="javascript:swfu_%1$s.cancelQueue();" class="upload_cancel" style="display: none;"><span>Cancel</span></a>
					<div class="upload_file" style="display: none;"><div class="progress"></div><span></span></div>
				</div>', 
			$this->_strip($this->form_name, false));
		printf("<div class='file_input' id=\"div_%s_degraded\"><input name=\"%s\" id=\"%s\" type=\"file\" class=\"field file%s\" onChange=\"file_name('%s')\"/></div>", 
			$this->_strip($this->form_name, false), 
			$this->form_name, $this->_strip($this->form_name), !$this->validates?' error':'', $this->_strip($this->form_name));

		if ($this->params['required']) {
			echo '<span class="req_text">Required</span></div>';
		}
		
        // now load the script stuff for the swf uploader
		printf('<script>
				var swfu_%1$s;
				
				$(document).ready(function(){
					swfu_%1$s = new SWFUpload({
								container: "%1$s",
					
								// Backend settings
								upload_target_url: "%2$s",
								file_post_name: "up_file",
								post_params: {bucket:"%4$s", path:"%5$s"%6$s},

								// Flash file settings
								file_size_limit : "%3$s",
								file_types : "*.*",	// or you could use something like: "*.doc;*.wpd;*.pdf",
								file_types_description : "All Files",
								file_upload_limit : "0", // Even though I only want one file I want the user to be able to try again if an upload fails
								file_queue_limit : "1", // this isn"t needed because the upload_limit will automatically place a queue limit

								// Flash Settings
								flash_url : "/js/swfupload.swf",	// Relative to this file

								// UI settings
								ui_container_id : "div_%1$s",
								degraded_container_id : "div_%1$s_degraded",

								// Debug settings
								debug: false	
							});});
				</script>', 
				$this->_strip($this->form_name, false), $this->params['swf_uploader'], $this->params['max_size'], $this->params['bucket'], $this->params['path'],
				isset($this->params['dynamic'])?sprintf(', dynamic:"%s"', urlencode(serialize($this->params['dynamic']))):'');
		echo "</div>";
	}

	public function xss_safe($text = NULL) {
		return true;
	}
	
	public function _strip($string,$replace='_') {
		return preg_replace('/[^A-Za-z0-9]/',$replace,$string);
	}
	
	public function _array_name($array) {
		$tmp_array = array();
		
		foreach($array as $key => $value) {
			$tmp_array[$key][$this->name] = $value;
		}
		
		return $tmp_array;
	}
	
}

?>