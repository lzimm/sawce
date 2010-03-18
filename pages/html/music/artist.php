<?php
	global $artist;
	global $albums;
	global $tag;
	global $tag_add;

    $status = $artist->get_status();
    
	$albums = array_slice($albums, 0);

	$genres = $artist->get_genres();
	$tags = $artist->get_genres(false);
    $genre_tags = array_merge($genres, $tags); 
	
	$top_songs = $artist->get_top_songs(5);
	$top_spreaders = $artist->get_spreaders(5);
    $top_mavens = $artist->get_mavens(5);
    
    $stats = $artist->simple_stats();                                                                                                                                                                                                                                                                                                                                                                                                                                    
?>
                       
<?php define_header_part(1, 'Artists', build_link('music','artists')); ?>
<?php define_header_part(2, $artist->_display_name); ?>  
<?php define_header_link('edit', build_link('my','edit'), 
    (Util::check_authed() && ($artist->_id == Util::as_authed()->_id))); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="panel" style="background: #000 url(/img/justice_gradient_black.jpg) no-repeat;" class="page shdw_bot">
	
<div id="artist_panel_wrapping">
    <div class"prop"></div>
    <div class="info"><div class="bg"><div>
        <h1><?=$artist->_display_name?></h1>
        <p><?=isset($artist->_profile['profile']) ? $artist->_profile['profile'] : ''?></p>
		<p class="show_albums"><a href="<?=build_link('music','albums',$artist->_username)?>">Browse Albums</a></p>
    </div></div></div>

    <div id="artist_panel">
        <div class="head"><h1>
			<?=$artist->_display_name?> <span class="status"><?=$status['status']?></span>
		</h1></div>

        <div class="body">
            <table cellspacing="0" cellpadding="0"><tr>
            <td class="first" width="50%" valign="top">
            <h4>Top Songs</h4>

<?php foreach($top_songs as $song) { echo (Render::song($song)); } ?>

            </td>
            <td width="50%" valign="top">
            <h4>Genres &amp; Tags</h4>
            <p>

<?php foreach($genre_tags as $tag) { ?>            
            
                <?=$tag?>

<?php } ?> 
            
            </p>
            </td>
            </tr></table>
      	</div>
    </div>

</div>

</div>

<div id="trans_panel" class="page">
    <table cellspacing="0" cellpadding="0"><tr>
    <td class="first" width="50%">
    <h4>Top Mavens</h4>
    <ul>

<?php foreach($top_mavens as $maven) { ?>

        <li><?=$maven['username']?></li>
    
<?php } ?>

    </ul>
    </td>
    <td width="50%">
    <h4>Top Connectors</h4>
    <ul>

<?php foreach($top_spreaders as $spreader) { ?>

        <li><?=$spreader['connector']?></li>
    
<?php } ?>
    
    </ul>
    </td>
    </tr></table>
</div>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Tools</div>
    <div class="header">Sharing</div>
    <div class="section">You can share this everywhere</div>
</div></div>

<?php end_peripheral_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the lightbox preload
***********************************************************************/?>
<?php start_lightbox_preload(); ?>

<div id="lb_gear">
	<a href="javascript:close_light_box();" class="close"><span>Close (X)</span></a>
	<a href="javascript:lb_right();" class="arrow right"><img src="/img/lb_right.gif"></a>
	<a href="javascript:lb_left();" class="arrow left"><img src="/img/lb_left.gif"></a>
<div id="lb_panel">
	<div class="padding">
    <div class="header">Albums</div>
    <div class="body">
    
<?php foreach($albums as $album) { $songs = array_slice($album->get_songs(), 0); ?>   
        
        <div class="album">

<?php
    if ($album->_art == 'none') {
        echo "<img src='http://sawceart.s3.amazonaws.com/default-100.gif' class='art' />";
    } else {
        echo "<img src='http://sawceart.s3.amazonaws.com/" . $album->_artist . "/" . $album->_id . "-100." . $album->_art . "' class='art' />";
    }
?>
        
        <ul>
            <li class="album_title"><?=$album->_album_name?></li>

<?php foreach ($songs as $song) { ?>

            <li><?=Render::song($song)?></li>

<?php } ?>

        </ul>
        <div class="clear"></div>
        </div>
        
<?php } ?>  
    </div>
</div></div>
</div>

<?php end_lightbox_preload(); ?>      