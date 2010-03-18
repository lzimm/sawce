<?php
	global $song;
	global $artist;
?>

<lb_width>600</lb_width>
<lb_height>100</lb_height>
<lb_html><![CDATA[

<?php include('incs/ajalheader.php'); ?>

<h2>Purchase</h2>
<table cellspacing="0" cellpadding="0" border="0" table><tr><td>
<p><strong><?=$song->_song_name?></strong> by <?=$artist->_display_name?> ($<?=$song->_price?>)?<p>

<a href="<?=build_link('music','buy',$song->_id)?>"><span>Buy Now</span></a>
<a href="#" onClick="return light_box('<?=build_link_type(CTYPE_AJAL,'music','cart',$song->_id,'add')?>');"><span>Add to Cart</span></a>
<a href="javascript:close_light_box();"><span>Cancel</span></a>
<div class="clear"></div>
</td></tr></table>

<?php include('incs/ajalfooter.php'); ?>

]]></lb_html>
