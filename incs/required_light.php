<?php
	
	// builds a link according to the structure of the site
	function build_link($page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$link = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $page;
		
		if (isset($section)) {
			$link .= '/' . $section;
		}
		
		if (isset($id)) {
			$link .= '/' . $id;
		}
		
		if (isset($action)) {
			$link .= '/' . $action;
		}

		if (isset($key)) {
			$link .= '/' . $key;
		}
		
		if (isset($vars)) {
			$link .= '/' . $vars;
		}
		
		return $link;
	}

	// builds a link according to the structure of the site
	function build_link_type($ctype, $page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$link = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ctype . ':' . $page;
		
		if (isset($section)) {
			$link .= '/' . $section;
		}
		
		if (isset($id)) {
			$link .= '/' . $id;
		}
		
		if (isset($action)) {
			$link .= '/' . $action;
		}

		if (isset($key)) {
			$link .= '/' . $key;
		}
		
		if (isset($vars)) {
			$link .= '/' . $vars;
		}
		
		return $link;
	}
		
?>
