<?php

	global $id;
	global $start_time;
	global $transitional_menu;
	
	$transitional_menu = array();
	$transitional_menu['Download CSV'] = build_link('my','stats',$id,'csv',$start_time);
	$transitional_menu['Withdraw Funds'] = build_link('my','withdraw');
	$transitional_menu['My Balance'] = build_link('my','balance');

?>