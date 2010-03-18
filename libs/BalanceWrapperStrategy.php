<?

class BalanceWrapperStrategy extends WrapperStrategy {
	
	public $_balance = 0.00;
	public $_pending = 0.00;
	
	public function wrap($row) {	
		switch ($row['type']) {		
			case 'credit':
				if ((time() - strtotime($row['time'])) > $GLOBALS['cfg']['credit_pending']) {
					$this->_balance += $row['value'];
				} else {
					$this->_pending += $row['value'];
				}
			break;
			
			case 'fill';
				$this->_balance += $row['value'];
			break;
			
			case 'debit':
			case 'withdraw';
				$this->_balance -= $row['value'];
			break;
		}
			
		return null;
	}
	
}

?>
