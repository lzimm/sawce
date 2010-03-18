<?

class ProfileValidator extends Validator {

	public function validate() {
		$this->val = mysql_escape_string($this->val);

		if ($this->strategy) {
			return $this->strategy->validate($this->val);
		}

		return true;
	}
	
	public function build() {
		$i = 1;
		
		$value = str_replace("\\n", "<n />", $this->val);
		$value = str_replace("\\r", "", $value);
		$value = stripslashes($value);
		$value = str_replace("<n />", "\n", $value);
		
		if (!$this->validates) {
			echo '<div class="editor_msg error">Illegal Characters have been found and may have been stripped out of the following fields. Please verify any changes</div>';
		}
		
		foreach(container_parse($value) as $postItem) {
			echo '<div class="postItem" id="postItem'.$i.'"><div class="postCat"><label>Field</label>';
			echo '<input id="postCat'.$i.'" type="text" value="'.$postItem[0].'" onfocus="this.select();" /><a href="javascript:postItemDrop('.$i.');"></a>';
			echo '<div class="hints"></div>';
			echo '<div class="clear"></div></div>';
			echo '<div class="postBody">';
			if ($this->xss_safe($postItem[1])) {
				echo '<textarea id="postBody'.$i.'">'.$postItem[1].'</textarea></div></div>';
			} else {
				echo '<textarea id="postBody'.$i.'" class="error">'.$postItem[1].'</textarea></div></div>';
			}
			$i++;
		}
		printf("<script>postItems = %s;</script>", $i);		
	}
	
}

?>