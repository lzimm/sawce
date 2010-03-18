<?php

	global $transitional_menu;
	global $id;
	
	global $user;
	
	$transitional_menu = array();
	$transitional_menu['Message'] = build_link('people','message',$id);
	
    if ((Util::as_authed()->_id != $user->_id) && (!Util::check_authed() || (Util::check_authed() == UTYPE_ARTIST))) {
        $transitional_menu['Give Me a Song to Spread'] = build_link('people','give',$id);
		$transitional_menu['Buy My Favorites'] = build_link('music','pack','sawce',$id);   
    }
    
	if ($user && $user instanceof Artist) {
		$transitional_menu['Albums'] = build_link('music','artist',$id);
	}                                                               
	
?>