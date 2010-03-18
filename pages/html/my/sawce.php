<?php
	global $user;
	global $sawce;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($sawce as $i => $song) { ?>

<tr<?=($i==0)?" class='first_row'":"";?>>
<td width="40%" class="first">
	<a id="play_<?=$song->_id?>" class="play_btn rbl" href="javascript:play_track(<?=$song->_artist?>,<?=$song->_id?>,'<?=$song->_secret?>');"><span>Play</span></a>
	<a id="stop_<?=$song->_id?>" class="stop_btn rbl" href="javascript:stop_track(<?=$song->_id?>);"><span>Stop</span></a>
	<a href="<?=build_link('music','song', $song->_id)?>" class="title"><?=$song->_song_name?></a>
</td>
<td width="35%"><a href="<?=build_link('music','artist', $song->_artist_user)?>"><?=$song->_display_name?></a></td>
<td width="25%">
	<a href="<?=build_link('my','sawce', $song->_id, 'remove')?>">Remove</a>
	| <a href="<?=build_download_link($song->_artist, $song->_id, $song->_secret)?>" target="_blank">Download</a>
</td>
</tr>

<?php } ?>

</table>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">More</div>
    <div class="header">Account Management</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('my','balance')?>">My Balance</a></li> 
            <li><a href="<?=build_link('my','withdraw')?>">Withdraw Funds</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>