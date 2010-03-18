<?php
	
	// start the session
	session_start();
	
	if (!isset($_SESSION['errors'])) {
		$_SESSION['errors'] = array();
	}

	if (!isset($_SESSION['messages'])) {
		$_SESSION['messages'] = array();
	}
	
	// keep track of all the logics we've loaded
	global $final_logic;
	global $logics;
	
	$logics = array();
	$final_logic = false;

	// first thing we do is check for transparent authentication
	if ($_POST && isset($_POST['req_auth'])) {
		if (!Util::check_authed()) {
			global $u_email;
			global $u_pass;
			global $u_submit;
		
			$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
			$u_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
			$u_submit	= new SubmitValidator('req_auth', array('label' => 'Login'));
			
			try {
				if ($user = User::auth($u_email->get(), $u_pass->get())) {
					Util::auth_session($user);
					
					Util::user_message(MSG_USER_LOGIN);
				} else {
					Util::user_error(ERR_LOGIN_FAILED);
					
					set_auth_type();
				}
			} catch (Exception $e) {
				Util::catch_exception($e);
					
				set_auth_type();
			}
		}
						
		$_POST = array('req_auth' => true);
	}

	// check for transparent authentication via registration
	if ($_POST && isset($_POST['reg_auth'])) {
		if (!Util::check_authed()) {
			global $r_name;
			global $r_pass;
			global $r_pass_chk;
			global $r_email;
			global $r_display;
			global $r_profile;
			global $r_type;
			global $r_check;
			global $r_submit;
		
			$r_name 	= new TextValidator('name', array('max_len' => 16, 'note' => FNOTE_USER_NAME));
			$r_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
			$r_pass_chk	= new TextValidator('pass_chk', array('max_len' => 64, 'password' => TRUE));
			$r_email 	= new TextValidator('email', array(), new EmailValidationStrategy());
			$r_display	= new TextValidator('display_name', array('required' => FALSE, 'note' => FNOTE_DISPLAY_NAME));
			$r_profile	= new ProfileValidator('profile', array('required' => FALSE));	
			$r_type 	= new SelectValidator('type', array('max_len' => 12, 'default' => UTYPE_FAN,
														'options' => array(
															array('id' => 'fan', 'name' => 'Fan'),
															array('id' => 'artist', 'name' => 'Artist')
														)));
			$r_check	= new CheckValidator('check', array('lookfor' => 'email'));
			$r_submit	= new SubmitValidator('reg_auth', array('label' => 'Register'));
		
			try {
				$r_check->get();
				
				if ($r_pass->get() != $r_pass_chk->get()) {
					$r_pass->invalidate();
					$r_pass_chk->invalidate();
					Util::user_error(ERR_PASSWORD_MISMATCH);
					
					set_auth_type();
				}
                                  
                global $step_close;
				if ($r_type->get() == UTYPE_ARTIST) {
					$user = Artist::create($r_name->get(), $r_email->get(), $r_pass->get(), $r_profile->get(), $r_display->get());	
                    
                    Util::step_message(array(STPMSG_ARTIST_CREATE, 
                        array('Learn how it works' => build_link('about','spread'), 
                                'Upload your Music' => build_link('my','albums')),
                                $step_close));
				} else {
					$user = User::create($r_name->get(), $r_email->get(), $r_pass->get(), $r_profile->get(), $r_display->get());
                    
                    Util::step_message(array(STPMSG_USER_CREATE, 
                        array('Learn how it works' => build_link('about','spread')),
                                $step_close));	
				}
				
				Util::auth_session($user);
				
				Util::user_message(MSG_USER_REGISTER);
			} catch (Exception $e) {
				Util::catch_exception($e);
				
				set_auth_type();
			}
		}
		
		$_POST = array('reg_auth' => true);
	}
	
	function set_auth_type() {
		$GLOBALS['ctype'] = (isset($GLOBALS['ctype']) && $GLOBALS['ctype'] == CTYPE_AJAL)?CTYPE_AJAL_AUTH:CTYPE_HTML_AUTH;
	}
	
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
	
	// a really basic parser
	function container_parse($text) {
		$container = array();
		$d = array('{','}','{/','}');
	
		$parse = true;
		while (strlen($text) && $parse) {
			$parse = false;
						
			$name = substr($text, strpos($text, $d[0])+strlen($d[0]), strpos($text, $d[1])-strlen($d[1]));
			
			if (($name) && strpos($text, $d[2].$name.$d[3])) {
				$container[] = array($name, substr($text, strpos($text, $d[1])+strlen($d[1]), strpos($text, $d[2].$name.$d[3]) - (strpos($text, $d[1])+strlen($d[1]))));
				$text = substr($text, strpos($text, $d[2].$name.$d[3]) + strlen($d[2].$name.$d[3]), strlen($text) - (strpos($text, $d[2].$name.$d[3]) + strlen($d[2].$name.$d[3])));
				$parse = true;
			}
		}
		
		return $container;
	}
	
    // loads a helper file
    function load_helper($helper) {
        include('helpers/' . $helper . '.php');
    }

	// loads a component piece in place
	function load_component($component, $id = NULL) {
		if ($id && file_exists('components/' . $GLOBALS['ctype'] . '/' . $GLOBALS['page'] . '/' . $GLOBALS['section'] . '/' . $component . '/' . $id . '.php')) {
			include('components/' . $GLOBALS['ctype'] . '/' . $GLOBALS['page'] . '/' . $GLOBALS['section'] . '/' . $component . '/' . $id . '.php');
		} else if (file_exists('components/' . $GLOBALS['ctype'] . '/' . $GLOBALS['page'] . '/' . $GLOBALS['section'] . '/' . $component . '.php')) {
			include('components/' . $GLOBALS['ctype'] . '/' . $GLOBALS['page'] . '/' . $GLOBALS['section'] . '/' . $component . '.php');
		}
	}
	
	// loads a global component in place
	function load_global_component($component, $params) {
		if (file_exists('components/' . $GLOBALS['ctype'] . '/global/' . $component . '.php')) {
			$GLOBALS['components'][$component] = $params;
			include('components/' . $GLOBALS['ctype'] . '/global/' . $component . '.php');
		}
	}
    
	// logic management
	function define_logic($page, $section) {
		global $final_logic;
		global $logics;
	
		if (file_exists('logic/'.$page.'/'.$section.'.php')) {
			if (!$final_logic && (!isset($logics[$page]) || !isset($logics[$page][$section]))) {
			
				if (!isset($logics[$page])) {
					$logics[$page] = array();
				}
				$logics[$page][$section] = true;
				
				include('logic/'.$page.'/'.$section.'.php');
			
			}
		} else if (file_exists('logic/'.$page.'/index.php')) {
			if (!$final_logic && (!isset($logics[$page]) || !isset($logics[$page]['index']))) {

				if (!isset($logics[$page])) {
					$logics[$page] = array();
				}
				$logics[$page]['index'] = true;
				
				include('logic/'.$page.'/index.php');
				
			}
		}
	}
	
	// require login
	function require_login() {
		if (!isset($_POST['req_auth'])) {
			$_POST = array();

			global $u_email;
			global $u_pass;
			global $u_submit;
		
			$u_email	= ($u_email)?$u_email:new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
			$u_pass 	= ($u_pass)?$u_pass:new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
			$u_submit	= ($u_submit)?$u_submit:new SubmitValidator('req_auth', array('label' => 'Login'));
		}
		
		if (!isset($_POST['reg_auth'])) {
			$_POST = array();
			
			global $r_name;
			global $r_pass;
			global $r_pass_chk;
			global $r_email;
			global $r_display;
			global $r_profile;
			global $r_type;
			global $r_check;
			global $r_submit;
		
			$r_name 	= ($r_name)?$r_name:new TextValidator('name', array('max_len' => 16, 'note' => FNOTE_USER_NAME));
			$r_pass 	= ($r_pass)?$r_pass:new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
			$r_pass_chk	= ($r_pass_chk)?$r_pass_chk:new TextValidator('pass_chk', array('max_len' => 64, 'password' => TRUE));
			$r_email 	= ($r_email)?$r_email:new TextValidator('email', array(), new EmailValidationStrategy());
			$r_display	= ($r_display)?$r_display:new TextValidator('display_name', array('required' => FALSE, 'note' => FNOTE_DISPLAY_NAME));
			$r_profile	= ($r_profile)?$r_profile:new ProfileValidator('profile', array('required' => FALSE));	
			$r_type 	= ($r_type)?$r_type:new SelectValidator('type', array('max_len' => 12, 'default' => UTYPE_FAN,
														'options' => array(
															array('id' => 'fan', 'name' => 'Fan'),
															array('id' => 'artist', 'name' => 'Artist'),
														)));
			$r_check	= ($r_check)?$r_check:new CheckValidator('check', array('lookfor' => 'email'));
			$r_submit	= ($r_submit)?$r_submit:new SubmitValidator('reg_auth', array('label' => 'Register'));
		}
		
		set_auth_type();
	}
	
	// page redefinition
	function shift_page($page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$_POST = array();
		
		session_write_close();
		
		header(sprintf("Location: %s", build_link($page, $section, $id, $action, $key, $vars)));
	}

	// alternate page redefinition
	function shift_location($location) {
		$_POST = array();
		
		session_write_close();
		
		header(sprintf("Location: %s", HTTP_LINK_BASE . $location));
	}

	// secure page redefinition
	function shift_page_secure($page, $section = NULL, $id = NULL, $action = NULL, $key = NULL, $vars = NULL) {
		$_POST = array();
		
		session_write_close();
		
		header(sprintf("Location: %s", build_link_secure($page, $section, $id, $action, $key, $vars)));
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
		//return AMAZON_SONG_BASE . $artist . '/' . $secret . '.mp3';
        return build_link('download','song',$secret);
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
	
	// defines the page header
	function define_header($string = '') {
		global $header;
		$header = $string;
	}

    // modifies the default header breadcrumb
    function define_header_part($part, $label, $link = null) {
        global $header_parts;
        if (!$header_parts) {
            $header_parts = array();
        }
        
        $header_parts[$part] = array($label, $link);
    }
    
    // defines the page subheader link
    function define_header_link($label, $link, $show = true) {
        global $header_link;
        if ($show) {
            $header_link = sprintf("<a href='%s'>(%s)</a>", $link, $label);
        }
    }
	
	// sets up the output buffer to capture and build the focus content panel
	function start_focus_content() {
		ob_start(); 
	}
	
	function end_focus_content() {
		$output = ob_get_contents(); 
		ob_end_clean();
		
		global $focus;
		$focus = $output;
	}

	// sets up the output buffer to capture and build the focus content panel
	function start_peripheral_content() {
		ob_start(); 
	}
	
	function end_peripheral_content() {
		$output = ob_get_contents(); 
		ob_end_clean();
		
		global $peripheral;
		$peripheral = $output;
	}

	// sets up the output buffer to capture and build the footer content panel
	function start_footer_content() {
		ob_start(); 
	}
	
	function end_footer_content() {
		$output = ob_get_contents(); 
		ob_end_clean();
		
		global $footer;
		$footer = $output;
	}

    // sets up the output buffer to capture and build the lightbox content panel
    function start_lightbox_preload() {
        ob_start(); 
    }
    
    function end_lightbox_preload() {
        $output = ob_get_contents(); 
        ob_end_clean();
        
        global $lightbox_preload;
        $lightbox_preload = $output;
    }
	
	// parses the req into an appropriate array
	function parse_req($req) {
		$req = split(';', urldecode($req));
		$array = array();
		
		foreach($req as $value) {
			$value = split(':', $value);
			$array[trim(strtolower($value[0]))] = isset($value[1])?trim(strtolower($value[1])):null;
		}
		
		return $array;
	}
	
	// sets a custom render section template
	function render_custom($section) {
		global $render_custom;
		global $page;
		$render_custom = 'pages/' . $GLOBALS['ctype'] . '/' . $page . '/' . $section . '.php';
	}
	
	// includes the footer
	function insert_sawce_footer() {
		include('incs/htmlfooter.php');
	}
	
	// loads a graph file
	function load_page_cache($page, $section, $type = 'graph', $time = 86400) {
		global $cache_file;
		$cache_file = $GLOBALS['cfg']['basedir'] . 'pages/cache/'.$type.'/'.$page.'/'.$section.'.cache';
		
		$GLOBALS['cloud_type'] = $section;
		
		if (!file_exists($cache_file)) {
			error_log("rebuilding cache file: $cache_file");
			
			define_logic($page, 'index');
			include('pages/'.$type.'/'.$page.'/index.php');
		}

        if ((time() - filectime($cache_file)) > $time) { 
            error_log("dispatching cache file for rebuild: $cache_file");
            
		    $db =& new SQL();
            $qry = sprintf("SELECT * FROM cache_queue WHERE cache_file = '%s' and complete = '0' LIMIT 1;",
                        $cache_file);
            
            if (!$db->query($qry, SQL_INIT)) {
                error_log("[CACHE FAILURE]: failed check update on " . $cache_file);
            }
            
            if (!$db->record) {
                $qry = sprintf("INSERT INTO cache_queue (`cache_file`, `page`, `section`, `type`) VALUES ('%s', '%s', '%s', '%s');",
                                    $cache_file, $page, $section, $type);
                                    
                if (!$db->query($qry, SQL_NONE)) {
                    error_log("[CACHE FAILURE]: failed to dispatch update on " . $cache_file);
                }
            }
        }
        
		include($cache_file);
	}
	
	function microtime_float() {
    	list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
	}
    
    function clean_get($string) {      
		return mysql_escape_string(htmlentities(strtolower(urldecode($string))));
    }
    
    // loads a lightbox message at the end of the page
    function show_lbmsg($msg) {
        global $lbmsg;
        $lbmsg = file_get_contents('incs/ajalheader.php');
        $lbmsg .= "<p class='lbmsg'>" . $msg . "</p><div class='clear'></div>";
        
        $lbmsg = str_replace("\n", "", addslashes($lbmsg)); 
        $lbmsg = str_replace("\r", "", $lbmsg); 
    }
    
    // here we detect if there's been a post and make sure 
    // that we aren't getting cross-site request forgeries
    function validate_post_session() {
        global $valid_session;
        
        // keep in mind that $_POST gets cleared after a transparent login               
        if ($_POST && !$valid_session && 
                !((sizeof($_POST) == 1) && (isset($_POST['req_auth']) || isset($_POST['reg_auth'])))) {
            throw new Exception(ERR_INVALID_SESSION);
        }
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

	function render_page_menu($page, $label = null) {
		if (file_exists('menus/'.$page.'.php')) {
			include('menus/'.$page.'.php');
			
			global $page_menu;
			
			if ($page_menu) {
				$menu_html = '<ul>';
			}
			
			if ($label) {
				$menu_html .= sprintf('<li><strong>%s</strong></li>', $label);
			}
			
			foreach($page_menu as $label => $link) {
				if (is_array($link)) {
					$menu_html .= sprintf('<li><a href="%s">%s</a></li>', '/'.$link[0].'/'.$link[1], $label);
				} else {
					$menu_html .= sprintf('<li><a href="%s">%s</a></li>', '/'.$page.'/'.$link, $label);
				}
			}
			
			if ($page_menu) {
				$menu_html .= '</ul>';
			}
			
			return $menu_html;
		}
		
		else return false;
	}
	
	function render_transitional_menu($page, $section, $label = null) {
		if (file_exists('menus/'.$page.'/'.$section.'.php')) {
			include('menus/'.$page.'/'.$section.'.php');
			
			global $transitional_menu;
			
			if ($transitional_menu) {
				$menu_html = '<ul>';
			}
			
			if ($label) {
				$menu_html .= sprintf('<li><strong>%s</strong></li>', $label);
			}
			
			foreach($transitional_menu as $label => $link) {
				if (is_array($link)) {
					$menu_html .= sprintf('<li><a onClick="%s" class="%s" id="%s" href="%s">%s</a></li>', $link[3], $link[2], $link[1], $link[0], $label);
				} else {
					$menu_html .= sprintf('<li><a href="%s">%s</a></li>', $link, $label);
				}
			}
			
			if ($transitional_menu) {
				$menu_html .= '</ul>';
			}
			
			return $menu_html;
		}
		
		else return false;
	}
		
?>
