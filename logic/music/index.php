<?php

	global $id;  
    
    global $p_tag;  
	global $artists_new;
	global $artists_hot;
	global $songs_hot;
	global $tags;
	global $similar_tags;
	
	global $search;
	global $field;
	global $submit;
	
	global $explore;
	global $explore_btn;
	
	$search	= new TextValidator('search', array('max_len' => 256, 'required' => false));
	$field	= new SelectValidator('field', array('max_len' => 12, 'default' => 'name',
													'options' => array(
														array('id' => 'name', 'name' => 'Artist Name'),
														array('id' => 'genre', 'name' => 'Artist Genre')
													)));
	$submit	= new SubmitValidator('submit', array('label' => 'Search'));

    if ($_POST && isset($POST['tag'])) {
        $p_tag = $_POST['tag'];
    } else {
        $p_tag = $id;
    }
	
	$explore		= new TextValidator('tag', array('max_len' => 256, 'required' => true, 'preset' => $p_tag));
	$explore_btn	= new SubmitValidator('explore', array('label' => 'Explore',
														'onClick' => 'return tag_explore();'
													));
	
	$tags = Extractor::get_tags(3);
	$artists_new = Extractor::get_new_artists(5);
	$artists_hot = Extractor::get_hot_artists(5);
	$songs_hot = Extractor::get_hot_songs(5);
	$similar_tags = Extractor::similar_tags($tags[0]['genre'], 4);
	
	if ($user = Util::as_authed()) {
		
	}
	
?>
