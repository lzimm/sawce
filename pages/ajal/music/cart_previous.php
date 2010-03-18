<?php
	global $song;
	global $artist;
?>

<lb_width>600</lb_width>
<lb_height>100</lb_height>
<lb_html><![CDATA[

<?php include('incs/ajalheader.php'); ?>

<h2>Not Added to Cart</h2>
<table cellspacing="0" cellpadding="0" border="0" table><tr><td>
<p>You already have <strong><?=$song->_song_name?></strong> by <?=$artist->_display_name?> in your cart<p>

<a href="<?=build_link('music','pack','cart')?>"><span>Check Out</span></a>
<a href="javascript:close_light_box();"><span>Continue Browsing</span></a>
<div class="clear"></div>
</td></tr></table>

<?php include('incs/ajalfooter.php'); ?>

]]></lb_html>
