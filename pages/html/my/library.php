<?php
	global $user;
	global $library;
	
	global $id;
	global $action;
	global $key;
	global $reqstring;
	
	global $search_song;
	global $search_album;
	global $search_artist;
	global $search;
	
	global $next;
	
	$song_dir = ($action == 'song') ? (($key == 'asc') ? 'desc' : 'asc') : 'asc';
	$artist_dir = ($action == 'artist') ? (($key == 'asc') ? 'desc' : 'asc') : 'asc';
	$time_dir = ($action == 'time') ? (($key == 'asc') ? 'desc' : 'asc') : 'desc';
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<table cellspacing="0" cellpadding="0" class="list_control">
	<tr>
	<td width="40%" class="first"><a href="<?=build_link('my','library',$id,'song',$song_dir,$reqstring)?>">Song Name</a></td>
	<td width="35%"><a href="<?=build_link('my','library',$id,'artist',$artist_dir,$reqstring)?>">Artist Name</a></td>
	<td width="25%"><a href="<?=build_link('my','library',$id,'time',$time_dir,$reqstring)?>">Purchase Time</a></td>
	</tr>
</table>

<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($library as $i => $song) { ?>

<tr<?=($i==0)?" class='first_row'":"";?>>
<td width="65%" class="first">
<?=Render::song_item($song, array(array(build_download_link($song->_artist, $song->_id, $song->_secret), 'Download'),
								array(build_link('my','sawce', $song->_id, 'add'), 'Spread')))?>
</td>
<td width="35%"><a href="<?=build_link('music','artist', $song->_artist_user)?>"><?=$song->_display_name?></a></td>
</tr>

<?php } ?>

</table>

<?php load_global_component('paginate', array(	'next' => $next, 
												'prev' => ($id > 0),
												'nextlink' => build_link('my','library',$id + 1,$action,$key,$reqstring),
												'prevlink' => build_link('my','library',$id - 1,$action,$key,$reqstring))); ?>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">More</div>
    <div class="header">Filter Songs</div>
    <div class="section">
        <form action="<?=build_link('my','library', $id)?>" method="post" class="makeform">
			<table class="makebasic">
				<tr><td><label>Song Name</label><?=$search_song->build();?></td></tr>
				<tr><td><label>Album Name</label><?=$search_album->build();?></td></tr>
				<tr><td><label>Artist Name</label><?=$search_artist->build();?></td></tr></tr>
				<tr><td><?=$search->build();?></td></tr>
			</table>
		</form>
    </div>
</div></div>

<?php end_peripheral_content(); ?>