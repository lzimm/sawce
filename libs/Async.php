<?php

class Async {
	
	public static function extraction_create($file) {	
		$db =& new SQL();
		
		$qry = sprintf("INSERT INTO extractor_messages (status, info) VALUES ('CREATE', '%s');", $file);
		if (!$db->query($qry, SQL_NONE)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		$id = $db->get_id();
		
		exec('php ' . $GLOBALS['cfg']['basedir'] . 'scripts/extractor.php ' . $id . ' > /dev/null &');
		//proc_close(proc_open ($GLOBALS['cfg']['basedir'] . 'scripts/extractor.php ' . $id . ' &', array(), $foo));
		
		return $id;
	}
	
	public static function extraction_check($key) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT * FROM extractor_messages WHERE job = '%s' ORDER BY time DESC", $key);
		if (!$db->query($qry, SQL_INIT)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		switch ($db->record['status']) {
			case 'ERR_ZIP_OPEN':
			case 'ERR_ZIP_EXTRACT':
				throw new FileException(constant($db->record['status']));
				return false;
			break;
			
			case 'MSG_ZIP_COMPLETE';
				return MSG_ZIP_COMPLETE;
			break;
			
			default:
				return false;
			break;
		}
	}
    
    public static function send_messages($user, $filter, $where, $subject, $message) {
        $db =& new SQL();
        
        $qry = sprintf("INSERT INTO messenger_messages (`user`, `filter`, `where`, `subject`, `message`) VALUES ('%s', '%s', '%s', '%s', '%s');",
                            $user, $filter, $where, $subject, $message);
                            
        if (!$db->query($qry, SQL_NONE)) {
            throw new DatabaseException($db->error);
            return false;
        }
        
        $id = $db->get_id();
        
        exec('php ' . $GLOBALS['cfg']['basedir'] . 'scripts/messenger.php ' . $id . ' > /dev/null &'); 
		//proc_close(proc_open ($GLOBALS['cfg']['basedir'] . 'scripts/messenger.php ' . $id . ' &', array(), $foo));        

        return $id;
    }
	
}

?>