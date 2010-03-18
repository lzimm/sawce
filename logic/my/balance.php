<?php

	global $id;
	global $req;
	global $user;
	global $credits;
	global $submit;
    global $min;
	
	$user = Util::as_authed();
	
	$min = '0.00';
	if (isset($req['min']) && ($req['min'] > $user->_balance)) {
		$min = sprintf("%1\$.2f", ($req['min'] - $user->_balance));
	}
	
	$credits 	= new TextValidator('credits', array('max_len' => 256, 
								'default' => $min,
								'currency' => true));
	$submit		= new SubmitValidator('submit', array('label' => 'Purchase Credits'));
	
	if ($id == 'confirm') {
        $credits = new HiddenValidator('credits', array('max_len' => 256, 
                                'default' => $min,
                                'currency' => true));
                                
		render_custom('balance_confirm');
	}

?>
