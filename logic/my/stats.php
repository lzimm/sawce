<?php

	global $id;
	global $action;
	global $req;

	global $user;

	global $stats;
	global $earnings;
	global $start_time;
	
	global $set_size;
	global $date_string;
	global $sec_diff;
	global $bar_width;
	
	global $date_select;
	global $date_submit;
	
	global $period_total;
	
	$user = Util::as_authed();

	$date_submit = new SubmitValidator('get', array('label' => 'Get Stats'));
	
	switch ($id) {
		case 'hourly':
			$date_select = new TextValidator('date', array('required' => false, 'default' => date('Y/m/d H:00', time())));
			$start_time = strtotime($date_select->get());
			$set_size = 24;
			$date_string = 'H:00';
			$bar_width = 37;
			$sec_diff = 60*60;
			$earnings	= $user->get_hourly_earnings(date('Y-m-d H:i:s', $start_time));
		break;

		case 'monthly':
			$date_select = new TextValidator('date', array('required' => false, 'default' => date('M Y', time())));
			$start_time = strtotime($date_select->get());
			$set_size = 12;
			$date_string = 'Y/m';
			$bar_width = 76;
			$sec_diff = 60*60*24*30;
			$earnings	= $user->get_monthly_earnings(date('Y-m-d H:i:s', $start_time));
		break;
		
		case 'daily':
		default:
			$date_select = new TextValidator('date', array('required' => false, 'default' => date('Y/m/d', time())));
			$start_time = strtotime($date_select->get());
			$set_size = 30;
			$date_string = 'Y/m/d';
			$id = 'daily';
			$bar_width = 30;
			$sec_diff = 60*60*24;
			$earnings	= $user->get_daily_earnings(date('Y-m-d H:i:s', $start_time));
		break;
	}
	
	$period_total = $earnings[sizeof($earnings) - 1]['sum'];
	$end_time = $start_time - $set_size*$sec_diff;
	
	$stats 	= $user->get_sales_info(date('Y-m-d H:i:s', $end_time), date('Y-m-d H:i:s', $start_time));
	
	if ($action == 'csv') {
		Util::build_csv($stats, array('song_name' => 'SONG', 'username' => 'USER', 'time' => 'TIME', 'value' => 'VALUE'));
	}
	
?>