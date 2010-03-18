<?php
	$section = isset($_GET['section'])?$_GET['section']:'index';
	
	global $id;
	global $tags;
	global $spreaders;

	global $search;
	global $field;
	global $submit;

	global $explore;
	global $explore_btn;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>



<?php end_focus_content(); ?>