<?php
    global $id;
    
    global $related_tags;
    global $related_artists;
    global $related_songs;
?>

<div style="width: 625px; height: 30px; display: block;"><h2><?=$id?> <span>(<a href="<?=build_link('music','tag',$id)?>">more</a>)</span></h2>
    <a class="rbl" href="javascript:map_overlay_close();"><span>Close Tag</span></a>
    <div class="clear slim"></div>
</div>

<div style="width: 200px; padding-right: 10px; float: left;">
<div class="rc_tb rc_666 map_curves">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    	<h3>Related Tags</h3>
    	<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($related_tags as $i => $tag) { ?>
	
		<tr<?=($i==0)?" class='first_row'":"";?>>
		<td><a href="<?=build_link('music','tag',$tag['genre'])?>" onclick="return map_click(this);"><?=$tag['genre']?></a></td>
		</tr>

<?php } ?>

		</table>
	</div>
	<div class="rc_b"><div><div><div></div></div></div></div>
</div>

<div class="rc_tb rc_666 map_curves">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    	<h3>Related Artists</h3>
    	<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($related_artists as $i => $artist) { ?>
	
		<tr<?=($i==0)?" class='first_row'":"";?>>
		<td><a href="<?=build_link('music','artist',$artist->_username)?>"><?=$artist->_display_name?></a></td>
		</tr>

<?php } ?>

		</table>
	</div>
	<div class="rc_b"><div><div><div></div></div></div></div>
</div>
</div>

<div style="width: 410px; float: left;">
<div class="rc_tb rc_666 map_curves">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    	<h3>Related Songs</h3>
    	<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($related_songs as $i => $song) { ?>
	
		<tr<?=($i==0)?" class='first_row'":"";?>>
		<td width="20%">
			<a id="play_<?=$song->_id?>" class="play_<?=$song->_id?> play_btn rbl" href="javascript:play_track(<?=$song->_artist?>,<?=$song->_id?>);"><span>Play</span></a>
			<a id="stop_<?=$song->_id?>" class="stop_<?=$song->_id?> stop_btn rbl" href="javascript:stop_track(<?=$song->_id?>);"><span>Stop</span></a>
		</td>
		<td width="80%">
			<a href="<?=build_link('music','song', $song->_id)?>" class="title"><?=$song->_song_name?></a>
			<a href="<?=build_link('music','artist', $song->_artist_user)?>" class="artist"><?=$song->_display_name?></a>
		</td>
		</tr>

<?php } ?>

		</table>
	</div>
	<div class="rc_b"><div><div><div></div></div></div></div>
</div>
</div>