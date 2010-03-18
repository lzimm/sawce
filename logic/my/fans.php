<?php

	global $id;
	global $action;
	global $req;
	
	if (($type = Util::check_authed()) == UTYPE_ARTIST) {
		global $user;
		global $subject;
		global $body;
		global $submit;
		global $filtered;
		global $fans;
		global $fan_count;
		global $filter;
		global $filter_submit;
		
		if (isset($_POST['filtered'])) {
			$filter = unserialize(urldecode($_POST['filtered']));
		} else if (isset($req['filtered'])) {
			$filter = unserialize(urldecode($req['filtered']));
		}
		
		if (!$filter) {
			$filter = array();
			$filter['date_min']		= new TextValidator('filter_date_min', array('required' => false,
																				'max_len' => 32, 
																				'sql' => '`last` >=', 
																				'timeparse' => true));
																				
			$filter['date_max']		= new TextValidator('filter_date_max', array('required' => false,
																				'max_len' => 32,
																				'sql' => '`last` <=', 
																				'timeparse' => true));
																				
			$filter['songs_min']	= new TextValidator('filter_songs_min', array('required' => false,
																				'max_len' => 32,
																				'sql' => '`count` >='));
																				
			$filter['songs_max']	= new TextValidator('filter_songs_max', array('required' => false,
																				'max_len' => 32,
																				'sql' => '`count` <='));
																				
			$filter['spread_min']	= new TextValidator('filter_spread_min', array('required' => false,
																				'max_len' => 32,
																				'sql' => '`spread` >='));
																				
			$filter['spread_max']	= new TextValidator('filter_spread_max', array('required' => false,
																				'max_len' => 32,
																				'sql' => '`spread` <='));
			
			$filter['adoption_min']	= new TextValidator('filter_adoption_min', array('required' => false,
																				'max_len' => 32,
																				'prepart' => true,
																				'sql' => 'DATEDIFF(song_rights.time, songs.released) >='));
			
			$filter['adoption_max']	= new TextValidator('filter_adoption_max', array('required' => false,
																				'max_len' => 32,
																				'prepart' => true,
																				'sql' => 'DATEDIFF(song_rights.time, songs.released) <='));
																				
			//$filter['global_min']	= new TextValidator('filter_global_min', array('max_len' => 32));
			//$filter['global_max']	= new TextValidator('filter_global_max', array('max_len' => 32));
		}
		
		$filter_submit = new SubmitValidator('submit', array('label' => 'Filter'));
		
		$filter_string = '';
		$where_string = '';
		foreach($filter as $filter_part) {
			try {				
				if (($val = $filter_part->get()) !== '') {
					if (isset($filter_part->params['timeparse'])) {
						$val = date('Y-m-d H:i:s', strtotime($val));
					}
					
					if (!isset($filter_part->params['prepart'])) {
						$filter_string .= sprintf("%s%s '%s'", $filter_string?' AND ':'', $filter_part->params['sql'], $val);
					} else {
						$where_string .= sprintf("%s%s '%s'", $where_string?' AND ':'', $filter_part->params['sql'], $val);
					}
				}
			} catch (Exception $e) {
				Util::catch_exception($e);
			}
		}
		
		$user = Util::as_authed();
		$fans = $user->get_fans(25, $filter_string, '', $where_string);
		$fan_count = $user->get_fan_count($filter_string, $where_string);
		
		$subject	= new TextValidator('subject', array('max_len' => 256));
		$body		= new TextValidator('body', array('max_len' => 500, 'type' => 'text'));
		$submit		= new SubmitValidator('submit', array('label' => 'Send'));
		$filtered	= new HiddenValidator('filtered', array('preset' => urlencode(serialize($filter))));
		
		if ($id == 'connect') {
			try {
				global $hidden_subject;
				global $hidden_body;
				$hidden_subject		= new HiddenValidator('subject', array('preset' => $subject->get()));
				$hidden_body		= new HiddenValidator('body', array('preset' => $body->get()));	
				
				if (($action == 'confirm') && ($_POST)) {
					try {
		                if (Async::send_messages($user->_id, mysql_escape_string($filter_string), mysql_escape_string($where_string), $subject->get(), $body->get())) {
						    Util::user_message(MSG_MESSAGES_SENT);
							shift_page('my','fans');
					    }
		            } catch (Exception $e) {
		                Util::catch_exception($e);   
		            }
				}
			
				global $confirm_submit;						
				$confirm_submit = new SubmitValidator('confirm_send', array('label' => 'Confirm'));
			
				render_custom('fans_confirm');
			} catch (Exception $e) {
				Util::catch_exception($e);
			}
		}
	} else {
		Util::user_error(ERR_ARTIST_ONLY);
		shift_page('my', 'home');
	}
	
?>
