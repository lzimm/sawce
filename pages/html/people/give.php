<?php
	global $grant;
	global $library;
	
	global $id;
	global $action;
	global $key;
	global $reqstring;
	
	global $sort_page;
	global $sort_by;
	global $sort_order;	
	
	global $search_song;
	global $search_album;
	global $search;
	
	global $next;
	
	$song_dir = ($sort_by == 'song') ? (($sort_order == 'asc') ? 'desc' : 'asc') : 'asc';
	$album_dir = ($sort_by == 'album') ? (($sort_order == 'asc') ? 'desc' : 'asc') : 'asc';
	$time_dir = ($sort_by == 'time') ? (($sort_order == 'asc') ? 'desc' : 'asc') : 'desc';
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>



<?php end_focus_content(); ?>