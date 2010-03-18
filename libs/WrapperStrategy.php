<?php

abstract class WrapperStrategy {

	public $_db = null;

	public function __construct($db = null) {
		$this->_db = $db;
	}
	
	public abstract function wrap($row);
	
}

?>
