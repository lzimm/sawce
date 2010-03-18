<?php
	global $song_album;
	global $song_name;
	global $song_price;
	global $song_share;
	global $song_save;
	global $song;

	global $similar_songs;
	global $spreaders;
	
	global $genre;
	global $genre_add;
	
	$genres = $song->get_genres();
?>

<?php define_header_part(1, 'Albums', build_link('my','albums')); ?>
<?php define_header_part(2, $song->_album_name, build_link('my','album', $song->_album)); ?>
<?php define_header_part(3, $song->_song_name); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="song" class="page shdw_bot">
    <div class="title">
        <div class="art"><a href="<?=build_link('my','album',$song->_album);?>">

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
        
        <div class="options"><?=Render::song_options($song, true)?></div>
    </div>
	
	<div class="tags">
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td valign="top" width="65%"><label>Tags</label>

<?php foreach ($genres[0] as $i => $s_genre) { printf('%s%s', $i ? ', ' : '', $s_genre); } ?>
<?php foreach ($genres[1] as $i => $s_genre) { printf('%s%s', $i ? ', ' : sizeof($genres[0]) ? ', ' : '', $s_genre); } ?>
            
            </td>
            <td valign="top" width="35%">
                <form action="<?=build_link('my','song', $song->_id, 'tag')?>" method="post" class="makeform">
                <table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
                <tr class="first_row">
                    <td><label>Add a Genre</label><?=$genre->build();?></td>
                    <td><label>Save</label><?=$genre_add->build();?></td>
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
    <h4>Edit Song</h4>
    <form action="<?=build_link('my','song', $song->_id, 'edit')?>" method="post" class="makeform" id="sw_ajax">
    
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%" class="first"><label>Edit Song Name</label><?=$song_name->build();?></td>
            <td width="50%"><label>Edit Song Album</label><?=$song_album->build();?></td>
        </tr>
        <tr>
            <td width="50%" class="first"><label>Edit Song Price</label><?=$song_price->build();?></td>
            <td width="50%"><label>Edit Song Commission Rate</label><?=$song_share->build();?></td>
        </tr>
        <tr>
            <td width="50%" class="first">&nbsp;</td>
            <td width="50%"><label>Save Changes</label><?=$song_save->build();?></td>
        </tr>
    </table>
    
    </form>	
</div>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Info</div>
    <div class="header">Top Fans</div>
    <div class="section">
    <ul>
<?php foreach($spreaders as $i => $spreader) { ?> 

        <li<?=$i?'':' class="first"'?>><a href="<?=build_link('people','profile',$spreader['connector'])?>"><?=$spreader['connector']?></a></li>

<?php } ?>    
    </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>