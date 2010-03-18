<?php

// define the query types
define('SQL_NONE', 1);
define('SQL_ALL', 2);
define('SQL_INIT', 3);

class SQL {
    
	static protected $db = null;
	var $error = null;
	var $record = null;
	var $result = null;
	
	static protected $keepalive = false;
    
    // constructor
	function __construct() {
		if (!SQL::$db) {
			SQL::$db = mysqli_connect($GLOBALS['cfg']['dbhost'],$GLOBALS['cfg']['dbuser'],$GLOBALS['cfg']['dbpass'], $GLOBALS['cfg']['dbname']);
		}
	}
	
	function __destruct() {
		if (!SQL::$keepalive) {
			$this->disconnect();
		}
	}
    
    // disconnect
	function disconnect() {
		if (SQL::$db) {
			@mysqli_free_result($this->result);
			@mysqli_close(SQL::$db);
			SQL::$db = NULL;
		}
	}
	
	function keepalive($mode = true) {
		SQL::$keepalive = $mode;
	}
    
	// query
	function query($query, $type = SQL_NONE, $strategy = null, $limit = 0, &$next = null) {	
		if (!SQL::$db || !(@mysqli_ping(SQL::$db))) {
			$this->__construct();
		}
		
		$this->record = array();
		$_data = array();
        
		$start = microtime_float();
		$this->result = mysqli_query(SQL::$db, $query);
		
		switch ($type) {
			case SQL_ALL:
				// get all the records
				
				$count = 0;
				while($_row = mysqli_fetch_assoc($this->result)) {
					$count++;
					if (!$limit || $count <= $limit) {
						$_data[] = $strategy?$strategy->wrap($_row):$_row;
					} else if ($count <= $limit) {
						$next = true;
					}  
				}           
				$this->record = $_data;
				
				mysqli_free_result($this->result);
				break;
			case SQL_INIT:
				// get the first record
				$_row = mysqli_fetch_assoc($this->result);
				$this->record = $strategy?$strategy->wrap($_row):$_row;
				
				mysqli_free_result($this->result);
				break;
			case SQL_NONE:
			default:
				// records will be looped over with next()
				break;   
		}
		
		// todo: figure out why SQL::db is NULL here when building graphs through Extractor::generate_tag_matrix()
		if (mysqli_error(SQL::$db) != '') {
			$this->error = mysqli_error(SQL::$db) . ' : ' . $query;
			return false;
		}
		
		$runtime = microtime_float() - $start;
		if ($runtime > 0.5) {
			error_log(sprintf("<!-- [%s] %s -->", $runtime, $query));
		}
		
		return true;
	}
	
	// insert id
	function get_id() {
		return mysqli_insert_id(SQL::$db);
	}
	
	public function __sleep() {
		$this->__destruct();
		return array('error', 'record', 'result');
	}
	
	function next() {
		return mysqli_fetch_assoc($this->result);
	}
	
}

?>
