<?php

	global $transitional_menu;
	global $id;
	
	$transitional_menu = array();
	$transitional_menu['Upload Individual Files'] = build_link('my','album',$id);
    $transitional_menu['Embed and Spread'] = array(build_link('embed','album',$id),
												'embed', 'rbl lbox', '');
	$transitional_menu['Delete'] = build_link('my','album',$id,'delete');

?>