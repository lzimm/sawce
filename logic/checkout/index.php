<?php
	global $id;
	global $req;
	global $user;
	global $credits;
	
	global $f_name;
	global $l_name;
	global $addr_1;
	global $addr_2;
	global $city;
	global $province;
    global $postal;
	global $country;
	
	global $card_type;
	global $card_num;
	global $card_mnth;
    global $card_year;
	
	global $submit;
	
	$user = Util::as_authed();
	
	$credits = new HiddenValidator('credits', array('max_len' => 256, 
								'currency' => true));
	
	try {
		$credit_amount = $credits->get();
        
	    if (!$credit_amount || ($credit_amount == '0.00')) {
            throw new Exception(ERR_NO_AMOUNT_SPECIFIED);
        }
		
        $f_name 	= new TextValidator('f_name', array('max_len' => 256, 'tabindex' => 1));
		$l_name 	= new TextValidator('l_name', array('max_len' => 256, 'tabindex' => 2));
		$addr_1 	= new TextValidator('addr_1', array('max_len' => 256, 'tabindex' => 3));
		$addr_2 	= new TextValidator('addr_2', array('max_len' => 256, 'required' => false, 'tabindex' => 4));
		$city 		= new TextValidator('city', array('max_len' => 256, 'tabindex' => 5));
		$province 	= new TextValidator('province', array('max_len' => 256, 'tabindex' => 6));
        $postal     = new TextValidator('postal', array('max_len' => 256, 'tabindex' => 7));
		$country 	= new TextValidator('country', array('max_len' => 256, 'tabindex' => 8));

		$card_type 	= new SelectValidator('card_type', array('tabindex' => 9,
																'label' => 'label', 
																'options' => array(
																	array('id' => 'visa', 'label' => 'Visa'),
																	array('id' => 'mc', 'label' => 'MasterCard'),
																	array('id' => 'amex', 'label' => 'American Express')
																),
																'default' => 'visa'));
		$card_num 	= new TextValidator('card_num', array('max_len' => 256, 'tabindex' => 10));
		$card_mnth 	= new SelectValidator('card_mnth', array('tabindex' => 11,
																'label' => 'label', 
																'options' => array(
																	array('id' => '01', 'label' => '01'),
																	array('id' => '02', 'label' => '02'),
																	array('id' => '03', 'label' => '03'),
																	array('id' => '04', 'label' => '04'),
																	array('id' => '05', 'label' => '05'),
																	array('id' => '06', 'label' => '06'),
																	array('id' => '07', 'label' => '07'),
																	array('id' => '08', 'label' => '08'),
																	array('id' => '09', 'label' => '09'),
																	array('id' => '10', 'label' => '10'),
																	array('id' => '11', 'label' => '11'),
																	array('id' => '12', 'label' => '12')
																),
																'default' => date('m', time())));
        
		// use 2 digit date format for PSIGate
		$card_year  = new SelectValidator('card_year', array('tabindex' => 12,
																'label' => 'label', 
																'options' => array(
																	array('id' => '2007', 'label' => '07'),
																	array('id' => '2008', 'label' => '08'),
																	array('id' => '2009', 'label' => '09'),
																	array('id' => '2010', 'label' => '10'),
																	array('id' => '2011', 'label' => '11'),
																	array('id' => '2012', 'label' => '12'),
																	array('id' => '2013', 'label' => '13'),
																	array('id' => '2014', 'label' => '14'),
																	array('id' => '2015', 'label' => '15'),
																	array('id' => '2016', 'label' => '16')
																),
																'default' => date('Y', time())));

		$submit		= new SubmitValidator('submit', array('label' => 'Confirm', 'tabindex' => 13));
		
		if ($section == 'confirm') {
            try {
				/* cybersource code
                load_helper('cybs');                         
                $config = cybs_load_config('cybs.ini');

                $capture = false;
                $request = array();
 
                $request['ccAuthService_run'] = 'true';
                $request['merchantReferenceCode'] = 'MRC-14344';
                
                $request['billTo_firstName'] = $f_name->get();
                $request['billTo_lastName'] = $l_name->get();
                $request['billTo_street1'] = $addr_1->get();
                $request['billTo_street2'] = $addr_2->get();
                $request['billTo_city'] = $city->get();
                $request['billTo_state'] = $province->get();
                $request['billTo_postalCode'] = $postal->get();
                $request['billTo_country'] = $country->get();
                $request['billTo_ipAddress'] = $_SERVER['REMOTE_ADDR'];
                $request['billTo_email'] = $user->_email;
                $request['shipTo_firstName'] = $f_name->get();
                $request['shipTo_lastName'] = $l_name->get();
                $request['shipTo_street1'] = $addr_1->get();
                $request['shipTo_street2'] = $addr_2->get();
                $request['shipTo_city'] = $city->get();
                $request['shipTo_state'] = $province->get();
                $request['shipTo_postalCode'] = $postal->get();
                $request['shipTo_country'] = $country->get(); 
                $request['card_accountNumber'] = $card_num->get();
                $request['card_expirationMonth'] = $card_mnth->get();
                $request['card_expirationYear'] = $card_year->get();
                $request['purchaseTotals_currency'] = 'USD';
                
                $request['item_0_unitPrice'] = $credit_amount;
                $request['item_1_unitPrice'] = $GLOBALS['cfg']['fees']['transaction'];
				$request['item_2_unitPrice'] = Util::compute_taxes($credit_amount + $GLOBALS['cfg']['fees']['transaction']);

                $requestID = runAuth($config, $request);

                if ($requestID) {
                    $request = array();
                    $request['ccCaptureService_run'] = 'true';
                    $request['merchantReferenceCode'] = 'MRC-14344';
                    $request['ccCaptureService_authRequestID'] = $requestID;
                    $request['purchaseTotals_currency'] = 'USD';
                    
                    $request['item_0_unitPrice'] = $credit_amount;
                    $request['item_1_unitPrice'] = $GLOBALS['cfg']['fees']['transaction'];
					$request['item_2_unitPrice'] = Util::compute_taxes($credit_amount + $GLOBALS['cfg']['fees']['transaction']);
                    
                    $capture = runCapture($config, $requestID, $request);
                }*/
				
				$total = $credit_amount + $GLOBALS['cfg']['fees']['transaction'] + Util::compute_taxes($credit_amount + $GLOBALS['cfg']['fees']['transaction']);
				$psi = new PsiGatePayment();

				//$psi->setGatewayURL('https://secure.psigate.com:7934/Messenger/XMLMessenger'); // Set URL or use 'https://dev.psigate.com:7989/Messenger/XMLMessenger' for testing
				$psi->setGatewayURL(PSIGATE_TRANSPORT_URL); // Set URL or use 'https://dev.psigate.com:7989/Messenger/XMLMessenger' for testing
				$psi->setStoreID(PSIGATE_STORE_ID);
				$psi->setPassPhrase(PSIGATE_PASS_PHRASE); // Assures authenticity
				//$psi->setOrderID(); // Order ID.  Leave blank to have PSiGate assign
				$psi->setPaymentType('CC');
				$psi->setCardAction('0'); // 1 for Authorize, 0 for Immediate Charge
				//$psi->setTaxTotal1('1.00'); // Tax value 1, ex Sales Tax
				//$psi->setTaxTotal2('2.00'); // Tax value 2, ex VAT
				//$psi->setTaxTotal3('3.00'); // Tax value 3, ex GST
				//$psi->setTaxTotal4('4.00'); // Tax value 4, ex PST
				//$psi->setTaxTotal5('5.00'); // Tax value 5
				//$psi->setShiptotal('10.00'); // shipping
				$psi->setSubTotal($total); // Amount
				$psi->setCardNumber($card_num->get()); // Card Number
				$psi->setCardExpMonth($card_mnth->get()); // Month in 2-digit format
				$psi->setCardExpYear($card_year->get()); // Year in 2-digit format
				$psi->setUserID($user->_id); // Unique customer identifier set by merchant.
				$psi->setBname($f_name->get() . ' ' . $l_name->get()); // Billing Name
				//$psi->setBcompany('Johns Store'); // Company Name
				$psi->setBaddress1($addr_1->get()); // Billing Address 1
				$psi->setBaddress2($addr_2->get()); // Billing Address 2
				$psi->setBcity($city->get()); // Billing City
				$psi->setBprovince($province->get()); // Billing state or province
				$psi->setBpostalCode($postal->get()); // Billing Zip
				$psi->setBcountry($country->get()); // Country Code - 2 alpha characters
				/*$psi->setSname('John Doe'); // Shipping Name
				$psi->setScompany('Johns Store'); // Shipping Company
				$psi->setSaddress1('123 Any Street'); // Shipping Address 1
				$psi->setSaddress2('Suite C'); // Shipping Address 2
				$psi->setScity('Battle Ground'); // Shipping City
				$psi->setSprovince('WA'); // Shipping state or province
				$psi->setSpostalCode('98604'); // Shipping Zip
				$psi->setScountry('US'); // Shipping country
				$psi->setPhone('555-555-1212'); // Customer Phone*/
				$psi->setEmail($user->_email); // Customer Email
				$psi->setComments('Order from Test store');  // comments, whatever you'd like
				$psi->setCustomerIP($_SERVER['REMOTE_ADDR']); // Customer IP address, for fraud
				$psi->setCardIDCode('0'); // Pass CVV code
				//$psi->setCardIDNumber('111'); // CVV code
			
				// Send transaction data to the gateway
				$psi_xml_error = (!($psi->doPayment() == PSIGATE_TRANSACTION_OK));
			
				// if it went through alright, update the database and be happy
				if ($psi_xml_error == PSIGATE_TRANSACTION_OK) {
	               	try {
	                	$credited = $user->add_balance($credit_amount);
	 					Util::user_message(MSG_BILLED_SUCCESS);
	              	} catch (Exception $e) {
	              		error_log(sprintf("[CREDIT FAILURE @ %s] UserID: %s, Amount: %s", date('Y-m-d H:i:s', time()), $user->_id, $credit_amount));
						Util::user_error(ERR_BILLED_INCOMPLETE);
					}

				    if (isset($req['next'])) {
					    shift_location($req['next']);
				    } else {
					    render_custom('receipt');
				    }
			    } else {
				    Util::user_error(sprintf(ERRTPL_BILLED_FAILURE, $psi->getTrxnErrMsg()));
			    }
            } catch (Exception $e) {
                Util::catch_exception($e);
            }
		}
	} catch (Exception $e) {
		Util::user_error(ERR_NO_AMOUNT_SPECIFIED);
		shift_page('my','balance');
	}
		
?>
