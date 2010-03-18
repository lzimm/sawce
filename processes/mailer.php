<?php

    include('/var/www/html/config/config.php');
    include('/var/www/html/incs/cache.php'); 
	$db =& new SQL();
	
	echo "[PROCESS START]: loading mailer";
	
	while (true) {		
		try {
			$qry = "SELECT * FROM message_queue WHERE sent = '0' ORDER BY time ASC;";
			if (!$db->query($qry, SQL_NONE)) {
				error_log("[PROCESS FAILURE]: mailer on $qry");
				exit();
			}
	
			$update = '';
			$count = 0;
			while ($row = $db->next()) {
				$to = $row['to_address'];
				$subject = $row['subject'];
				$body = $row['body'];
				$message = $row['message'];
	
				$TXTmsg = $body;
				$message = str_replace("\n", "<br />", $body);
				$HTMLmsg = $body;
		
				$mail = new PHPMailer();
		
				$mail->From = 'mail@sawce.net';
				$mail->Sender = 'mail@sawce.net';
				$mail->FromName = 'Sawce';
				$mail->Subject = $subject;
				$mail->Body = $TXTmsg;
				$mail->AddAddress($to);
		
				$mail->Send();
	
				$update .= sprintf("OR id = '%s' ", $row['id']);
				$count++;
			}
	
			if ($update) {
				$qry = sprintf("UPDATE message_queue SET sent = '1' WHERE %s;", substr($update, 3));
				if (!$db->query($qry, SQL_NONE)) {
					error_log("[PROCESS FAILURE]: mailer on $qry");
				}
				
				echo "[PROCESS UPDATE]: mailer sent $count messages";
			}
		} catch (Exception $e) {
			error_log("[PROCESS FAILURE]: mailer caught: " . $e->getMessage());
		}
		
		sleep(10);
	}	

?>