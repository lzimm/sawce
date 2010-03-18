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
<p>You already own <strong><?=$song->_song_name?></strong> by <?=$artist->_display_name?><p>

<a href="javascript:close_light_box();"><span>Continue Browsing</span></a>
<a href="<?=build_download_link($song->_artist, $song->_id, $song->_secret)?>" target="_blank"><span>Download</span></a>
<div class="clear"></div>
</td></tr></table>

<?php include('incs/ajalfooter.php'); ?>

]]></lb_html>
