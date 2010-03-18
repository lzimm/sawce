<?php

    include('/var/www/html/config/config.php');
    include('/var/www/html/incs/cache.php'); 
	$db =& new SQL();
	
	if (!isset($argv[1])) {
		die();
	}
	
	$id = $argv[1];
	
	$qry = sprintf("SELECT * FROM messenger_messages WHERE id = '%s';", $id);		
	if (!$db->query($qry, SQL_INIT)) {
		error_log("[SCRIPT FAILURE]: messenger script on $qry");
		exit();
	}

    $user = User::find_by_id($db->record['user']);
    $fans = $user->get_fans(0, $db->record['filter'], '', $db->record['where']);
    
    $subject = mysql_escape_string($db->record['subject']);
    $message = mysql_escape_string($db->record['message']);
	
    foreach ($fans as $fan) {
        try {
            $fan['user']->send_message($user->_id, $subject, $message);      
        } catch (Exception $e) {
            error_log(sprintf("[SCRIPT ERROR]: messenger script threw: %s", $e->getMessage())); 
        }
    }
    
	exit();
	
?>