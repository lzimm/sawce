<?php

	include('/var/www/html/config/config.php');
    include('/var/www/html/incs/cache.php'); 
	$db =& new SQL();
	
	$qry = "SELECT * FROM cache_queue WHERE complete = '0' ORDER BY time ASC;";
	if (!$db->query($qry, SQL_NONE)) {
		error_log("[CRON FAILURE]: cache script on $qry");
		exit();
	}
	
    global $cache_file;
    global $page;
    global $section;
    
	$update = '';
    while ($row = $db->next()) {
        $cache_file = $row['cache_file'];
        $page = $row['page'];
        $section = $row['section'];
        $type = $row['type'];
        
        error_log("[CACHE]: rebuilding " . $cache_file);
        
        $GLOBALS['cloud_type'] = $row['section']; 
        include($GLOBALS['cfg']['basedir'] . 'logic/' . $page . '/index.php');
        include($GLOBALS['cfg']['basedir'] . 'pages/' . $type . '/' . $page . '/index.php');
        
        $update .= sprintf("OR id = '%s' ", $row['id']); 
	}
	
	if (!$update) {
		exit();
	}
	
	$qry = sprintf("UPDATE cache_queue SET complete = '1' WHERE %s;", substr($update, 3));
	if (!$db->query($qry, SQL_NONE)) {
		error_log("[CRON FAILURE]: cache script on $qry");
		exit();
	}	

?>