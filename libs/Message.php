<?php

class Message {

	public $_id = NULL;
	public $_type = NULL;
	public $_subject = NULL;
	public $_from = NULL;
	public $_from_user = NULL;
	public $_from_name = NULL;
	public $_to = NULL;
	public $_to_user = NULL;
	public $_to_name = NULL;
	public $_body = NULL;
	public $_reply = NULL;
	public $_thread = NULL;
	public $_time = NULL;
	public $_read = NULL;
	public $_db = NULL;
	
	public function __construct($params) {
        foreach ($this as $key => $val) {
            if (isset($params[substr($key, 1)])) {
                $this->$key = $params[substr($key, 1)];
            }
        }
        
        $this->_db =& new SQL();
	}

	public function send($email) {
		$from = User::find_by_id($this->_from);
		
		$message = sprintf("%s sent you a message:\n\n", $this->_from_name);
		$message .= $this->_body;
		$message .= sprintf("\n\nvisit %s to reply", build_link('my','message',$this->_id));
		
		$qry = sprintf("INSERT INTO message_queue (`message`, `from`, `to`, `to_address`, `subject`, `body`, `type`)
							VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');",
							$this->_id, $this->_from, $this->_to, $email, $this->_subject, $message, $this->_type);

		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		return true;
	}

	public static function create($to, $from, $subject, $body, $type = 'message', $reply = 0, $thread = 0, $link_id = 0, $link_string = '') {
		$db =& new SQL();
		
		$qry = sprintf("SELECT * FROM users WHERE id = '%s' OR id = '%s' LIMIT 2;", $from, $to);		
		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}

		if ($db->record && sizeof($db->record == 2)) {
			$from_user = $db->record[0]['id'] == $from ? $db->record[0]['username'] : $db->record[1]['username'];
			$from_name = $db->record[0]['id'] == $from ? $db->record[0]['display_name'] : $db->record[1]['display_name'];
			$to_user = $db->record[0]['id'] == $to ? $db->record[0]['username'] : $db->record[1]['username'];
			$to_name = $db->record[0]['id'] == $to ? $db->record[0]['display_name'] : $db->record[1]['display_name'];
			$email = $db->record[0]['id'] == $to ? $db->record[0]['email'] : $db->record[1]['email'];
			
			$time = date('Y-m-d H:i:s', time());
			$qry = sprintf("INSERT INTO user_messages (`type`, `link_id`, `link_string`, `subject`, `from`, `to`, `body`, `reply`, `thread`, `time`)
							VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');", 
							$type, $link_id, $link_string, $subject, $from, $to, $body, $reply, $thread, $time);
			
			if ($db->query($qry)) {
				$id = $db->get_id();
				
                $message = new Message(array(
                                'id' => $id, 
                                'type' => $type, 
                                'link_id' => $link_id, 
                                'link_string' => $link_string, 
                                'subject' => $subject, 
                                'from' => $from, 
                                'from_user' => $from_user, 
								'from_name' => $from_name,
                                'to' => $to, 
                                'to_user' => $to_user, 
								'to_name' => $to_name,
                                'body' => $body, 
                                'reply' => $reply, 
                                'thread' => $thread, 
                                'time' => $time, 
                                'read' => 0
                                ));
                                
				$message->send($email);
				return $message;
			} else {
				throw new DatabaseException($db->error);
				return false;
			}
		} else {			
			throw new UserExistsException(ERR_INVALID_USER);			
			return false;
		}		
	}
	
	public static function get_messages_to($to, $limit = NULL, $offset = NULL, $read = NULL) {
		$db =& new SQL();
		
		$offset = $offset ? $offset : 0;
		$qry = sprintf("SELECT user_messages.*, user_from.username AS from_user, user_to.username AS to_user FROM 
							user_messages JOIN users AS user_from ON user_messages.from = user_from.id
							JOIN users AS user_to ON user_messages.to = user_to.id
							WHERE user_messages.to = '%s'%s ORDER BY time DESC%s;",
			$to, (!is_null($read)?sprintf(" AND user_messages.read = '%s'", $read):''), ($limit?sprintf(" LIMIT %s, %s", $offset, $limit):''));
		
		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		$messages = array();
		foreach ($db->record as $row) {
			$messages[] = new Message($row);
		}
		
		return $messages;
	}
	
	public static function get_messages_where($where, $limit = NULL, $offset = NULL, &$next = NULL) {
		$db =& new SQL();
		
		$offset = $offset ? $offset : 0;
		$qry = sprintf("SELECT user_messages.*, user_from.username AS from_user, user_to.username AS to_user FROM 
							user_messages JOIN users AS user_from ON user_messages.from = user_from.id
							JOIN users AS user_to ON user_messages.to = user_to.id
							WHERE %s ORDER BY time DESC%s;",
								$where, ($limit?sprintf(" LIMIT %s, %s", $offset, $limit + 1):''));

		if (!$db->query($qry, SQL_ALL)) {
			throw new DatabaseException($db->error);
			return false;
		}
		
		$messages = array();
		foreach ($db->record as $i => $row) {
			if ($i < $limit) {
				$messages[] = new Message($row);
			}
			
			if ($i == $limit) {
				$next = true;
			} else {
				$next = false;
			}
		}
		
		return $messages;
	}
	
	public static function find($id) {
		$db =& new SQL();
		
		$qry = sprintf("SELECT user_messages.*, user_from.username AS from_user, user_from.display_name AS from_name, 
							user_to.username AS to_user, user_to.display_name AS to_name FROM 
							user_messages JOIN users AS user_from ON user_messages.from = user_from.id
							JOIN users AS user_to ON user_messages.to = user_to.id WHERE user_messages.id = '%s' LIMIT 1;", $id);
							
		if (!$db->query($qry, SQL_INIT)) {
			throw new DataException($db->error);
			return false;
		}
		
		return new Message($db->record);
	}
	
	public function reply($body) {				
		return Message::create($this->_from, $this->_to, 're: ' . $this->_subject, $body, $this->_type, $this->_id, ($this->_thread?$this->_thread:$this->_id));	
	}
	
	public function read() {
		$qry = sprintf("UPDATE user_messages SET `read` = '1' WHERE `id` = '%s';", $this->_id);		
		if (!$this->_db->query($qry)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$this->_read = 1;
		
		return true;
	}
	
	public function get_thread() {
		$qry = sprintf("SELECT user_messages.*, user_from.username AS from_user, user_from.display_name AS from_name, 
							user_to.username AS to_user, user_to.display_name AS to_name FROM 
							user_messages JOIN users AS user_from ON user_messages.from = user_from.id
							JOIN users AS user_to ON user_messages.to = user_to.id 
							WHERE (user_messages.thread = '%s' OR user_messages.id ='%s') 
							AND user_messages.time < '%s' ORDER BY time DESC;", 
							$this->_thread ? $this->_thread : $this->_id, $this->_thread ? $this->_thread : $this->_id, $this->_time);
	
		if (!$this->_db->query($qry, SQL_ALL)) {
			throw new DatabaseException($this->_db->error);
			return false;
		}
		
		$messages = array();
		foreach ($this->_db->record as $row) {
			$messages[] = new Message($row);
		}
		
		return $messages;
	}

}

?>
