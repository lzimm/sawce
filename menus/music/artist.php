<?php

	global $transitional_menu;
	global $id;
	
	$transitional_menu = array();
	$transitional_menu['Profile'] = build_link('people','profile',$id);
	$transitional_menu['Similar Artists'] = build_link('music','artists','like',$id);
	
?>