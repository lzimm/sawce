<?php

	global $id;
	global $req;
	global $user;
	global $credits;
	global $email;
	global $submit;
	
	$user = Util::as_authed();
	
	$email 		= new TextValidator('email', array('max_len' => 256,
								'default' => $user->_email));
	$credits 	= new TextValidator('credits', array('max_len' => 256, 
								'default' => '0.00',
								'maxval' => $user->_balance,
								'currency' => true));
	$submit		= new SubmitValidator('submit', array('label' => 'Confirm'));
	
	try {
		$email->get();
		$credit_amount = $credits->get();
	} catch (Exception $e) {
		Util::catch_exception($e);
		$credit_amount = '0.00';
	}
	
	if (($id == 'confirm') && ($credit_amount > 0)) {
		$email		= new HiddenValidator('email', array('max_len' => 256));
        $credits 	= new HiddenValidator('credits', array('max_len' => 256, 
                                'default' => '0.00',
                                'currency' => true));
                                
		render_custom('withdraw_confirm');
	} else if (($id == 'complete') && ($credit_amount > 0)) {
		load_helper('paypal');
		
		try {
			if($user->withdraw_balance($credit_amount)) {

           		try {
					pp_pay($email->get(), $credit_amount);
					Util::user_message(MSG_PP_SUCCESS);
           		} catch (Exception $e) {
           			error_log(sprintf("[DEBIT FAILURE @ %s] UserID: %s, Amount: %s", date('Y-m-d H:i:s', time()), $user->_id, $credit_amount));
					Util::user_error(ERR_PP_INCOMPLETE);
				}

			} else {
				Util::user_error(ERR_PP_FAILURE);
			}
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
		
		render_custom('withdraw_complete');
	}

?>
