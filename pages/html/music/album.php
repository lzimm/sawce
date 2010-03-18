<?php
	global $album;
	global $artist;
	global $tag;
	global $tag_add;
	
	$songs = $album->get_songs();
	$genres	= $album->get_genres();
	$tags = $album->get_genres(false, false);
?>

<?php define_header_part(1, 'Artists', build_link('music','artists')); ?>
<?php define_header_part(2, $artist->_display_name, build_link('music','artist', $artist->_username)); ?>
<?php define_header_part(3, $album->_album_name); ?> 
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="album" class="page shdw_bot">
	<div class="art">

<?php
	if ($album->_art == 'none') {
		echo "<img src='http://sawceart.s3.amazonaws.com/default-200.gif' />";
	} else {
		echo "<img src='http://sawceart.s3.amazonaws.com/" . $album->_artist . "/" . $album->_id . "-200." . $album->_art . "' />";
	}
?>

	</div>
	
	<div class="songs">
	<h3><?=$album->_album_name?></h3>

<?php foreach ($songs as $a_song) { echo (Render::song($a_song)); } ?>
        
	</div>
	
	<div class="clear_left"></div>
</div>

<?=insert_sawce_footer();?> 

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Info</div>
    <div class="header">Tags</div>
    <div class="section">
        <p class="tags">

<?php foreach ($genres[0] as $i => $s_genre) { printf('%s%s', $i ? ', ' : '', $s_genre); } ?>
<?php foreach ($genres[1] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) ? ', ' : '', $s_genre); } ?>
<?php foreach ($tags[0] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) || sizeof($genres[1]) ? ', ' : '', $s_genre); } ?>
<?php foreach ($tags[1] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) || sizeof($genres[1]) || sizeof($tags[0]) ? ', ' : '', $s_genre); } ?>

        </p>
        <form action="<?=build_link('music','album', $album->_id, 'tag')?>" method="post" class="makeform" id="sw_ajax">
        
        <table class="makebasic" cellpadding="0" cellspacing="0">
            <tr><td width="70%"><label>Add a Tag</label><?=$tag->build();?></td>
            <td><label>Save</label><?=$tag_add->build();?></td></tr>
        </table>
        
        </form>
    </div>
</div></div>

<?php end_peripheral_content(); ?>