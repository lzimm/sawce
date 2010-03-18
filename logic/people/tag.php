<?php

    global $id;
    global $reqstring;
    
    global $related_tags;
    global $related_spreaders;
    
    if (!$id) {
    	$id = Util::easy_clean($reqstring);
    }

	if (!$id) {
		$tag = new TextValidator('tag', array('max_len' => 256, 'required' => false));
		try {
			$id = $tag->get();
		} catch (Exception $e) {
		}
	}

	global $next;
	$next = false;
    
    if ($id) {
		$related_tags = ($GLOBALS['ctype'] == CTYPE_AJAT) ? Extractor::similar_tags($id, 4) : Extractor::similar_tags($id, 10);
	    $related_spreaders = Extractor::spreaders_by_tag($id, 10);
    } else {
		Util::user_error(ERR_NO_TAG_SELECTED);
		shift_page('music','explorer');
    }

?>