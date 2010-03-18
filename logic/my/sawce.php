<?php

	global $id;
	global $action;

	global $user;
	global $sawce;
	
	$user 		= Util::as_authed();
	$sawce 		= $user->sawce_get();
	
	switch ($action) {
		case 'remove':
			try {
				$user->sawce_remove($id);
				$sawce = $user->sawce_get();
			} catch (Exception $e) {
				Util::catch_exception($e);	
			}
		break;
		
		case 'add':
			try {
				$user->sawce_add($id);
				$sawce = $user->sawce_get();
                
                Util::step_message(array(STPMSG_SAWCE_ADD,
                    array('Embed and Spread' => build_link('embed','sawce',$user->_username))));  
			} catch (IllegalSongException $e) {
				Util::user_error($e->getMessage());
				
				shift_page('music', 'buy', $id);
			} catch (Exception $e) {
				Util::catch_exception($e);	
			}
		break;				
	}
	
?>