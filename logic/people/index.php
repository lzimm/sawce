<?php

	global $id;
	global $tags;
	global $spreaders;
	
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
	
	$explore		= new TextValidator('tag', array('max_len' => 256, 'required' => true));
	$explore_btn	= new SubmitValidator('explore', array('label' => 'Explore',
														'onClick' => 'return tag_explore();'
													));
	
	$tags = Extractor::get_tags(10);
	$spreaders = Extractor::get_spreaders(10);	
	
	if ($user = Util::as_authed()) {
		
	}
	
?>
