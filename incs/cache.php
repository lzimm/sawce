<?php
    
	// autoloader for classes, don't have to load everything on every page
	function __autoload($class_name) {
		$class_name = str_replace('_', '/', $class_name);
		
		if (file_exists($GLOBALS['cfg']['basedir'] . 'libs/' . $class_name . '.php')) {
			require_once($GLOBALS['cfg']['basedir'] . 'libs/' . $class_name . '.php');
		} else {
			require_once($class_name . '.php');
		}
	}
	
	// money formating function	
	function format_money($number) {
		return number_format($number, 2, '.', ',');
	}	
	
	// converts strings from machined to humanized
	function humanize($string) {
		if (!$string) {
			$string = 'Home';
		}
		
		for ($i = 0; $i < strlen($string); $i++) {
			if ($i == 0) {
				$string[$i] = strtoupper($string[$i]);
			} else if ($string[$i] == '_') {
				$string[$i] = ' ';
			} else if ($string[$i-1] == ' ') {
				$string[$i] = strtoupper($string[$i]);
			} else {
				$string[$i] = strtolower($string[$i]);
			}
		}
		
		return $string;
	}
	
	// builds link to download song
	function build_download_link($artist, $song, $secret) {
		return AMAZON_SONG_BASE . $artist . '/' . $secret . '.mp3';
	}
	
	// helper function
	function is_blank($var) {
		if (is_null($var) || ($var === '')) {
			return true;
		} else {
			return false;
		}
	}
	
	// builds a link according to the structure of the site
	function build_link($page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$link = HTTP_LINK_BASE . $page;
		
		if (!is_blank($section)) {
			$link .= '/' . $section;
		}
		
		if (!is_blank($id)) {
			$link .= '/' . $id;
		}
		
		if (!is_blank($action)) {
			$link .= '/' . $action;
		}

		if (!is_blank($key)) {
			$link .= '/' . $key;
		}
		
		if (!is_blank($vars)) {
			$link .= '/' . $vars;
		}
		
		return $link;
	}

	// builds a link according to the structure of the site
	function build_link_type($ctype, $page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$link = HTTP_LINK_BASE . $ctype . ':' . $page;
		
		if (!is_blank($section)) {
			$link .= '/' . $section;
		}
		
		if (!is_blank($id)) {
			$link .= '/' . $id;
		}
		
		if (!is_blank($action)) {
			$link .= '/' . $action;
		}

		if (!is_blank($key)) {
			$link .= '/' . $key;
		}
		
		if (!is_blank($vars)) {
			$link .= '/' . $vars;
		}
		
		return $link;
	}

	// builds a secure link according to the structure of the site
	function build_link_secure($page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$link = HTTPS_LINK_BASE . $page;
		
		if (!is_blank($section)) {
			$link .= '/' . $section;
		}
		
		if (!is_blank($id)) {
			$link .= '/' . $id;
		}
		
		if (!is_blank($action)) {
			$link .= '/' . $action;
		}

		if (!is_blank($key)) {
			$link .= '/' . $key;
		}
		
		if (!is_blank($vars)) {
			$link .= '/' . $vars;
		}
		
		return $link;
	}

	// builds a secure link according to the structure of the site
	function build_link_type_secure($ctype, $page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$link = HTTPS_LINK_BASE . $ctype . ':' . $page;
		
		if (!is_blank($section)) {
			$link .= '/' . $section;
		}
		
		if (!is_blank($id)) {
			$link .= '/' . $id;
		}
		
		if (!is_blank($action)) {
			$link .= '/' . $action;
		}

		if (!is_blank($key)) {
			$link .= '/' . $key;
		}
		
		if (!is_blank($vars)) {
			$link .= '/' . $vars;
		}
		
		return $link;
	}
	
	function microtime_float() {
    	list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
	}

    // this is used for ID3 checking, to make sure that a field is related to
    // song info
    function shared_content($shared1, $shared2) {
        if (substr_count($shared1, $shared2) || substr_count($shared2, $shared1)) {
            return true;
        } else {
            return false;
        }
    }
		
?>
