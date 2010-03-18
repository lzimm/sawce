<?php
	global $songs;
	global $total;
	global $submit;
	global $id;
	global $key;
?>

<?php define_header_part(0, 'Spread Music'); ?>
<?php define_header_part(1, 'Spread Sawce'); ?>
<?php define_header_part(2, 'Buy Music'); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">                            
<h3>Buy Music</h3>

<form action="<?=build_link('music', 'pack', $id, 'confirm', $key)?>" method="post" class="makeform">

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="40%"><label>Song</label></td>
		<td width="40%"><label>Artist</label></td>
		<td width="20%"><label>Price</label></td>
	</tr>

<?php foreach ($songs as $song) { ?>
	<tr>
		<td width="40%">
            <?=Render::song_item($song, array(array(build_link('music', 'pack', $id, 'via', $key, 'remove:' . $song->_id), 'Remove')))?>
        </td>
		<td width="40%"><?=$song->_display_name?></td>
		<td width="20%">$<?=$song->_price?></td>
	</tr>	
<?php } ?>

	<tr>
		<td width="40%">&nbsp;</td>
		<td width="40%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
	</tr>

	<tr>
		<td width="40%">&nbsp;</td>
		<td width="40%"><label>Total</label></td>
		<td width="20%">$<?=$total?></td>
	</tr>

	<tr>
		<td width="40%">&nbsp;</td>
		<td width="40%"><label>Confirm</label></td>
		<td width="20%"><?=$submit->build();?></td>
	</tr>

</table>

</form>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>