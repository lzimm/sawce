<?php
	
	include("config/config.php");
    include("incs/required.php");
    
	try { 
        // we use this to detect cross-site request forgeries in conjunction 
        // with the validate_post_session() method
        if (isset($_POST['session_id']) && ($_POST['session_id'] != session_id())) {
			throw new Exception(ERR_INVALID_SESSION);
		} else if (isset($_POST['session_id'])) {
			global $valid_session;
			$valid_session = true;
		}
        
        // and we run by default
        validate_post_session();
		
		$get_order = array('page','section','id','action','key','req');
        $get = mysql_escape_string(substr($_SERVER['REQUEST_URI'], 1));
        $get = explode('/', $get);
        
        $index = 0;
        $reqset = false;
        while(($index < sizeof($get_order)) && !$reqset) {
            $part = $get_order[$index];
            
            if (!$index) {
                if (strstr($get[$index], ':')) {
                    $parts = explode(':', $get[$index]);
                    $GLOBALS['ctype'] = $ctype = clean_get($parts[0]);
                    $GLOBALS['page'] = $page = clean_get($parts[1]);
                } else {
                    $GLOBALS['page'] = $page = clean_get($get[$index]);
                }
            } else {
                if (!strstr($get[$index], ':')) {
                    $GLOBALS[$part] = $$part = clean_get($get[$index]);
                } else {
                    $GLOBALS['reqstring'] = $reqstring = clean_get(implode('/', array_slice($get, $index))); 
                    $GLOBALS['req'] = $req = parse_req(implode('/', array_slice($get, $index)));
                    $reqset = true;
                }
            }
            
            $index++;
        }                     
        
        $GLOBALS['ctype'] = $ctype = $ctype ? $ctype : CTYPE_HTML;
        $_GET['page'] = $GLOBALS['page'] = $page = $page ? $page : 'base';
        $_GET['section'] = $GLOBALS['section'] = $section = $section ? $section : 'index';
		$GLOBALS['iref'] = $page . '/' . $section . '/' . $id;
	
		// if we aren't hitting a public page and we aren't logged in, require a login
		// note that this will load a login form, which will get auto processed by the require script
		// that contains the logic for building the authentication
		if (!Util::check_authed() && !in_array($page, $GLOBALS['public_pages'])) {
			if (!isset($_POST['req_auth']) && !isset($_POST['reg_auth'])) {
				Util::user_error(ERR_NO_LOGIN);
			}

			require_login();
		} else {
			if (isset($_POST['req_auth']) || isset($_POST['reg_auth'])) {
				$_POST = array();

				if (in_array($page, $GLOBALS['public_pages']) && !Util::check_authed()) {
					// this is because its killing the form data unless we process it and load the logic
					// however, the logic loader is also causing a script bounce since we do need a shift
					// therefore we only fill this variable in if the login failed
					$GLOBALS['forced_auth'] = true;
				}
			}
		
			if ((($GLOBALS['ctype'] != CTYPE_GRAPH) && 
					!(isset($_SERVER['HTTPS']) && ($GLOBALS['ctype'] == CTYPE_HTML) && !in_array($page, $GLOBALS['secure_pages']))) ||
					(isset($GLOBALS['forced_auth']))) {	
							
				define_logic($page, $section);
			} else {
				shift_page($page, $section, $id, $action, $key, $reqstring);
			}
		}
	
		if (!(isset($_SERVER['HTTPS']) && ($GLOBALS['ctype'] == CTYPE_HTML) && !in_array($page, $GLOBALS['secure_pages']))) {
	
			if (($GLOBALS['ctype'] != CTYPE_SPREAD) && ($GLOBALS['ctype'] != CTYPE_GRAPH)) {
				$t_page = $page;
				if (!file_exists('pages/'.$GLOBALS['ctype'].'/'.$page)) {
					$t_page = 'base';
				}
		
				$t_section = $section;
				if (!file_exists('pages/'.$GLOBALS['ctype'].'/'.$page.'/'.$section.'.php')) {
					$t_section = 'index';
				}
		
				if ((($t_page != $page) || ($t_section != $section)) && ($GLOBALS['ctype'] != CTYPE_HTML_AUTH) && ($GLOBALS['ctype'] != CTYPE_AJAL_AUTH)) {		
					$GLOBALS['page'] = $page = $t_page;
					$GLOBALS['section'] = $section = $t_section;
			
					define_logic($page, $section);
				}
	
			}
	
			global $render_custom;

			switch($GLOBALS['ctype']) {	
				case CTYPE_HTML_AUTH:
					header("Content-type: text/html; charset=utf-8");
					include("incs/htmllogin.php");
				break;
		
				case CTYPE_HTML:
					header("Content-type: text/html; charset=utf-8"); 
					
					if ($render_custom) {
						include($render_custom);
					} else {
						include("pages/html/" . $page . "/" . $section . ".php");
					}
			
					include("incs/htmlpage.php");
				break;
		
				case CTYPE_XML:
					header("Content-Type: text/xml; charset=utf-8");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					
					if ($render_custom) {
						include($render_custom);
					} else {
						include('pages/xml/'.$page.'/'.$section.'.php');
					}	
				break;
		
				case CTYPE_AJ:
					if (isset($_SESSION['errors']) && $_SESSION['errors']) {
						echo 0;
					} else {
						echo 1;
					}

					$_SESSION['errors'] = array();
					$_SESSION['messages'] = array();
				break;
		
				case CTYPE_AJAT:
					header("Content-type: text/html; charset=utf-8");
					
					if (file_exists('pages/ajat/'.$page.'/'.$section.'.php')) {
						if ($render_custom) {
							include($render_custom);
						} else {
							include('pages/ajat/'.$page.'/'.$section.'.php');
						}	
					}
				break;

				case CTYPE_AJAL:
					header("Content-Type: text/xml; charset=utf-8");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			
					echo "<lb_res>\n";
					echo "<lb_msg><![CDATA[\n";
					Util::ajax_messages();
					echo "\n]]></lb_msg>";
			
					if (Util::check_authed()) {
						echo "<lb_script><![CDATA[logout_show();]]></lb_script>";
					}
			
					if (file_exists('pages/ajal/'.$page.'/'.$section.'.php')) {
						if ($render_custom) {
							include($render_custom);
						} else {
							include('pages/ajal/'.$page.'/'.$section.'.php');
						}	
					}
			
					echo "</lb_res>";
				break;

				case CTYPE_AJAL_AUTH:
					header("Content-Type: text/xml; charset=utf-8");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			
					echo "<lb_res>\n";
					echo "<lb_msg><![CDATA[\n";
					Util::ajax_messages();
					echo "\n]]></lb_msg>";
						
					include('incs/ajalauth.php');
			
					echo "</lb_res>";
				break;

				case CTYPE_AJAX:
					header("Content-Type: text/xml; charset=utf-8");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			
					echo "<sw_res>\n";
			
					echo "<sw_msg><![CDATA[\n";
					Util::ajax_messages();
					echo "\n]]></sw_msg>";
			
					if ($render_custom) {
						include($render_custom);
					} else {
						if (file_exists('pages/ajax/'.$page.'/'.$section.'.php')) {
							include('pages/ajax/'.$page.'/'.$section.'.php');
						}
					}
			
					echo "</sw_res>";
				break;
		
				case CTYPE_SPREAD:
					header("Content-type: text/html; charset=utf-8");
					
					if (file_exists('pages/spread/'.$section.'.php')) {
						include ('pages/spread/'.$section.'.php');
					}
				break;
		
				case CTYPE_GRAPH:
					if (file_exists('pages/graph/'.$page.'/'.$section.'.php')) {
						load_page_cache($page,$section);
					}
				break;
		
				default:
					if ($render_custom) {
						include($render_custom);
					} else {
						if (file_exists('pages/' . $GLOBALS['ctype'] . '/'.$page.'/'.$section.'.php')) {
							include('pages/' . $GLOBALS['ctype'] . '/'.$page.'/'.$section.'.php');
						}
					}
				break;
			}
	
		}
	
		session_write_close();	
	} catch (Exception $e) {
		include('incs/error.php');
		error_log($e->getMessage());
	}
	
?>
