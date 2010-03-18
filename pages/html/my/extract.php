<?php
    global $album;
    global $album_name;
    global $album_submit;

    global $zip_submit;
    global $zip;
    
    global $genre;
    global $genre_add;
    
    $songs     = $album->get_songs();
    $genres    = $album->get_genres();
?>

<?php define_header_part(1, 'Albums', build_link('my','albums')); ?>
<?php define_header_part(2, $album->_album_name); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="album" class="page shdw_bot">
    <div class="art">

<?php
    if ($album->_art == 'none') {
        echo "<img src='http://sawceart.s3.amazonaws.com/default-200.gif' />";
    } else {
        echo "<img src='http://sawceart.s3.amazonaws.com/" . $album->_artist . "/" . $album->_id . "-200." . $album->_art . "' />";
    }
?>

    <form action="<?=build_link('my','album', $album->_id, 'edit')?>" method="post" enctype="multipart/form-data" class="makeform" id="sw_ajax">
            
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td class="first"><label>Edit Album Name</label><?=$album_name->build();?></td></tr>
        <tr><td class="first"><label>Upload New Art</label><?=$album_art->build();?></td></tr>
        <tr><td class="first"><label>Save Changes</label><?=$album_submit->build();?></td></tr>
    </table>

    </form>

    </div>
    
    <div class="songs">
    <h3><?=$album->_album_name?></h3>

<?php foreach ($songs as $a_song) { echo (Render::song($a_song, true)); } ?>
        
    </div>
    
    <div class="clear_left"></div>
</div>

<div id="trans_panel" class="page">

<h4>Upload Music</h4>
        
<form action="<?=build_link('my','extract', $album->_id, 'upload')?>" method="post" enctype="multipart/form-data" class="makeform">
        
<table class="makebasic" cellpadding="0" cellspacing="0">
    <tr>
        <td width="80%" class="first"><label>Zip File</label><?=$zip->build();?></td>
        <td width="20%"><label>Upload</label><?=$zip_submit->build();?></td>
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
    <div class="right_top">Tools</div>
    <div class="header">Upload Options</div>
    <div class="section">
    <ul>
        <li class="first"><a href="<?=build_link('my','album',$album->_id)?>">Upload Individual Songs</a></li>
        <li><a href="<?=build_link('my','extract',$album->_id)?>">Upload Packages of Songs</a></li>
    </ul>
    </div>
    <div class="header">Genres</div>
    <div class="section">
        <p class="tags">
<?php for($i = 0; $i < sizeof($genres[0]); $i++) { ?>
    <a href="<?=build_link('music','tag',$genres[0][$i]);?>"><?=$genres[0][$i]?></a><?=$i<(sizeof($genres[0]) - 1)||$genres[1]?',':'';?> 
<?php } ?>

<?php for($i = 0; $i < sizeof($genres[1]); $i++) { ?>
    <a href="<?=build_link('music','tag',$genres[1][$i]);?>"><?=$genres[1][$i]?></a> <a href="<?=build_link('my','album', $album->_id, 'untag', 'genre:' . $genres[1][$i])?>">(X)</a><?=$i<(sizeof($genres[1]) - 1)?',':'';?> 
<?php } ?>
        </p>
        <form action="<?=build_link('my','album', $album->_id, 'tag')?>" method="post" class="makeform" id="sw_ajax">
        
        <table class="makebasic" cellpadding="0" cellspacing="0">
            <tr><td width="70%"><label>Add a Genre</label><?=$genre->build();?></td>
            <td><label>Save</label><?=$genre_add->build();?></td></tr>
        </table>
        
        </form>
    </div>
</div></div>

<?php end_peripheral_content(); ?>