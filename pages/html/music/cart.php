<?php
	global $song;
	global $artist;
	global $id;
	global $key;
	global $reqstring;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">                            
<h3>Add to Cart</h3>

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="40%"><label>Song</label></td>
		<td width="40%"><label>Artist</label></td>
		<td width="20%"><label>Price</label></td>
	</tr>

	<tr>
		<td width="40%"><?=$song->_song_name?></td>
		<td width="40%"><?=$song->_display_name?></td>
		<td width="20%">$<?=$song->_price?></td>
	</tr>

	<tr>
		<td width="40%">&nbsp;</td>
		<td width="40%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
	</tr>

</table>

<a href="<?=build_link('music','buy',$id);?>"><span>Buy Now</span></a>
<a href="<?=build_link('music','cart',$id,'add',$reqstring);?>"><span>Add to Cart</span></a>
<a href="<?=build_link(isset($req['pg'])?$req['pg']:'base');?>"><span>Cancel</span></a>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>