<?php

    include('/var/www/html/config/config.php');
    include('/var/www/html/incs/cache.php'); 
	$db =& new SQL();
	
	if (!isset($argv[1])) {
		die();
	}
	
	$id = $argv[1];
	
	$qry = sprintf("SELECT * FROM extractor_messages WHERE id = '%s';", $id);	
	if (!$db->query($qry, SQL_INIT)) {
		error_log("[SCRIPT FAILURE]: extractor script on $qry");
		exit();
	}
	
	$filename = $db->record['info'];
	$file = $GLOBALS['cfg']['basedir'] . $db->record['info'];
	$album = basename(dirname($db->record['info']));	
	$album = Album::find($album);
	
	$za = new ZipArchive();	
	if ($za->open($file) !== TRUE) {
		$qry = sprintf("INSERT INTO extractor_messages (job, status) 
			VALUES ('%s', 'ERR_ZIP_OPEN')", $id);
			
		if (!$db->query($qry, SQL_NONE)) {
			error_log("[SCRIPT FAILURE]: extractor script on $qry");
		}
		
		exit();
	}
	
	$za->extractTo(dirname($file));
	
	for ($i = 0; $i < $za->numFiles; $i++) {
		$za_file = $za->statIndex($i);
		$za_filename = dirname($filename) . '/' . $za_file['name'];
		
		try {
			Song::create($za_filename, $album->_artist, 'SONG_ID3_NAME', $album->_id, NULL, 'public', 0.99, 0.5, 0, 0, true);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	$za->close();
	
	$qry = sprintf("INSERT INTO extractor_messages (job, status) 
		VALUES ('%s', 'MSG_ZIP_COMPLETE')", $id);
		
	if (!$db->query($qry, SQL_NONE)) {
		error_log("[SCRIPT FAILURE]: extractor script on $qry");
	}
	
	exit();
	
?>