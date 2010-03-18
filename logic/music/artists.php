<?php

	global $id;
	global $action;
	global $artists;
	global $letters;
	
	$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	
	global $search;
	global $field;
	global $submit;
	
	global $next;
	
	global $reqstring;
	global $req;
	if (isset($req['value']) && isset($req['field'])) {
		$_POST['search'] = urldecode($req['value']);
		$_POST['field'] = $req['field'];
	}
	
	$search	= new TextValidator('search', array('max_len' => 256, 'required' => false));
	$field	= new SelectValidator('field', array('max_len' => 12, 'default' => 'name',
													'options' => array(
														array('id' => 'name', 'name' => 'Artist Name'),
														array('id' => 'genre', 'name' => 'Artist Genre')
													)));
	$submit	= new SubmitValidator('submit', array('label' => 'Search'));	
	
	if ($id == 'search') {
		$action = ((int) $action)?(int) $action:0;
		
		try {
			switch($field->get()) {			
				case 'genre':
					$artists = Extractor::artists_by_tag($search->get(), 25, $action, $next);
				break;

				case 'name':
				default:
					$artists = Artist::search($search->get(), $action, false, $next);
				break;
			}
			
			$reqstring = sprintf("field:%s;value:%s",$field->get(),urlencode($search->get()));
		} catch (Exception $e) {
			Util::catch_exception($e);	
		}
	} else if ($id == 'like') {
		$artists = Artist::like($action);
	} else {
		$action = ((int) $action)?(int) $action:0;
		
		$artists = Artist::search($id, $action, true, $next);
	}
	
?>
