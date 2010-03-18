<?php

	global $page_menu;
	
	$page_menu = array();
	$page_menu['Home'] = '';
	
	if (Util::as_authed() instanceof Artist) {
		$page_menu['Albums'] = 'albums';
		$page_menu['Fans'] = 'fans';
	} else {	
		$page_menu['Sawce'] = 'sawce';
	}
	
	$page_menu['Library'] = 'library';

?>