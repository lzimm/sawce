<?php
	global $artist;
	global $song;
	global $tag;
	global $tag_add;

	global $similar_songs;
	global $spreaders;
	
	$genres = $song->get_genres();
	$tags = $song->get_genres(false);
?>

<?php define_header_part(1, 'Artists', build_link('music','artists')); ?>
<?php define_header_part(2, $song->_display_name, build_link('music','artist', $song->_artist)); ?>
<?php define_header_part(3, $song->_album_name, build_link('music','album',$song->_album)); ?> 
<?php define_header_part(4, $song->_song_name); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="song" class="page shdw_bot">
    <div class="title">
        <div class="art"><a href="<?=build_link('music','album',$song->_album);?>">

<?php
    if ($song->_album_art == 'none') {
        echo "<img src='http://sawceart.s3.amazonaws.com/default-100.gif' class='album_art' />";
    } else {
        echo "<img src='http://sawceart.s3.amazonaws.com/" . $song->_artist . "/" . $song->_album . "-100." . $song->_album_art . "' class='album_art' />";
    }
?>

        </div></a>
        <div class="info">
            <?=$song->_song_name?> <span>($<?=$song->_price?>)</span>
            <div class="meta"><?=$song->_album_name?> <span>(<?=$song->_display_name?>)</span></div>
        </div>
        
        <div class="options"><?=Render::song_options($song, (Util::check_authed() && Util::as_authed()->check_rights($song->_id)))?></div>
    </div>
    
    <div class="tags">
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td valign="top" width="65%"><label>Tags</label>

<?php foreach ($genres[0] as $i => $s_genre) { printf('%s%s', $i ? ', ' : '', $s_genre); } ?>
<?php foreach ($genres[1] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) ? ', ' : '', $s_genre); } ?>
<?php foreach ($tags[0] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) || sizeof($genres[1]) ? ', ' : '', $s_genre); } ?>
<?php foreach ($tags[1] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) || sizeof($genres[1]) || sizeof($tags[0]) ? ', ' : '', $s_genre); } ?>
            
            </td>
            <td valign="top" width="35%">
                <form action="<?=build_link('music','song', $song->_id, 'tag')?>" method="post" class="makeform">
                <table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
                <tr class="first_row">
                    <td><label>Add a Tag</label><?=$tag->build();?></td>
                    <td><label>Save</label><?=$tag_add->build();?></td>
                </tr>
                </table>
                </form>
            </td>
        </tr>
    </table>    
    </div>
    
    <div class="clear_left"></div>
</div>

<div id="trans_panel" class="page">
<table cellspacing="0" cellpadding="0"><tr>
<tr><td width="30%" valign="top" class="first"><h4>Recommended Songs</h4></td>
<td width="70%" valign="top" colspan="3"><h4>Important Fans</h4></td></tr><tr>
<td width="30%" valign="top" class="first"><ul>
<?php foreach($similar_songs as $i => $r_song) { ?>  
    <li<?=$i?'':' class="first"'?>><a href="<?=build_link('music','song',$r_song->_id)?>"><?=$r_song->_song_name?></a></li>
<?php } ?>
</ul></td>
<td width="35%" valign="top">
<ul>
<?php foreach($spreaders as $i => $spreader) { ?>  
    <li<?=$i?'':' class="first"'?>><?=$spreader['maven']?></li>
<?php } ?>
</ul></td>
<td width="10" valign="top">
<ul class="join">
<?php foreach($spreaders as $i => $spreader) { ?>  
    <li<?=$i?'':' class="first"'?>>to</li>
<?php } ?>
</ul>
</td>
<td width="35%" valign="top">
<ul>
<?php foreach($spreaders as $i => $spreader) { ?>  
    <li<?=$i?'':' class="first"'?>><?=$spreader['connector']?></li>
<?php } ?>
</ul></td>
</tr></table>
</div>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Info</div>
    <div class="header">Get Involved</div>
    <div class="section">
    <p><a href="<?=build_link('about','spread')?>" class="license license_0">Like this track? Once you buy it, you'll be able to become a real part of it by spreading it to all your friends (or just people who think you have awesome taste in music). <span class="term">Earn <span>&nbsp;$<?=format_money($song->_commission*$song->_price*(1-$GLOBALS['cfg']['rates']['commission']['seller']))?>&nbsp;</span> each time you help promote this song</span></a></p>
    </div>
</div></div>

<?php end_peripheral_content(); ?>