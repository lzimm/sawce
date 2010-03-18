<?php
	global $albums;
	global $album_name;
	global $album_submit;
?>

<?php define_header(); ?>
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
		<div class="info"><a href="<?=build_link('my','album',$album->_id)?>" class="title"><?=$album->_album_name?> <span class="aux">(<?=sizeof($album->get_songs())?> songs)</span></a></div>	
		<div class="clear_left"></div>
	</div>

<?php } ?>

</div>

<?php end_focus_content(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Tools</div>
    <div class="header">Create an Album</div>
    <div class="section">
		<form action="<?=build_link('my','albums','create')?>" method="post" class="makeform" id="sw_ajax">
		
		<table class="makebasic" cellpadding="0" cellspacing="0">
			<tr><td><label>Album Name</label><?=$album_name->build();?></td></tr>
			<tr><td><label>Create</label><?=$album_submit->build();?></td></tr>
		</table>
		
		</form>
    </div>
</div></div>

<?php end_peripheral_content(); ?>