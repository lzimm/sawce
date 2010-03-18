<?php
	global $albums;
	global $artist;
?>

<?php define_header_part(1, 'Artists', build_link('music','artists')); ?>
<?php define_header_part(2, $artist->_display_name, build_link('music','artist',$artist->_username)); ?>
<?php define_header_part(3, 'Albums'); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="albums" class="page shdw_bot">

<?php foreach ($albums as $album) { ?>
	
	<div class="album">
		<div class="art"><?php
		if ($album->_art == 'none') {
			echo "<a href='" . build_link('my','album',$album->_id) ."'><img src='http://sawceart.s3.amazonaws.com/default-50.gif' /></a>";
		} else {
			echo "<a href='" . build_link('my','album',$album->_id) ."'><img src='http://sawceart.s3.amazonaws.com/" . $album->_artist . "/" . $album->_id . "-50." . $album->_art . "' /></a>";
		} ?></div>
		<div class="info"><a href="<?=build_link('music','album',$album->_id)?>" class="title"><?=$album->_album_name?> <span class="aux">(<?=sizeof($album->get_songs())?> songs)</span></a></div>	
		<div class="clear_left"></div>
	</div>

<?php } ?>

</div>

<?=insert_sawce_footer();?> 

<?php end_focus_content(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Tools</div>
    <div class="header">More</div>
    <div class="section">

    </div>
</div></div>

<?php end_peripheral_content(); ?>