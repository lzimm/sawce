<?php
	global $user;
	global $status;
	global $library;
	global $sawce;
	global $artists;
	global $messages;
?>

<?php define_header_part(1, 'Dashboard'); ?>   
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="status_panel" class="page shdw">
<div><?=$user->_display_name?></div>
<div><a href="<?=build_link('my','status')?>" class="status" onClick="return ipe_link(this);"><?=$status['status']?><span>Update</span></a></div>
<div class="clear_left"></div>
</div>

<div id="my_panel" class="page shdw_bot">

<div id="earnings_graph">

<?php 
	$earnings_max = $earnings[sizeof($earnings) - 1]['max'];
	$index = sizeof($earnings) - 2;

	for ($i = 0; $i < 30; $i++) { 
	    $date = date('Y/m/d', time() - 86400*(29-$i));
	    if (($index >= 0) && (date('Y/m/d', strtotime($earnings[$index]['time'])) == $date)) {
	        $earned = $earnings[$index]['sum'];
	        $height = $earnings[$index]['sum']/$earnings_max*200 . 'px';
	        $index--;
	    } else {
	        $earned = 0;
	        $height = '5px';
	    }
?>

<div class="stat" style="left: <?=$i*3.333;?>%; width: 3.333%; height: <?=$height?>;"> 
    <div class="stats">$<?=sprintf("%1\$.2f", $earned)?> (<?=$date?>)</div>
</div>

<?php } ?>

<div class="options"><a href="<?=build_link('my','stats')?>">Detailed Stats</a></div>
</div>

<div id="earnings_sub">
<table cellspacing="0" cellpadding="0" class="makebasic">
    <tr>
        <td class="first" width="25%" valign="top">
            <label>Current Account Balance</label>
            $<?=sprintf("%1\$.2f",$user->_balance)?>
        </td>
        <td width="25%" valign="top">
            <label>Pending Earnings</label>
            $<?=sprintf("%1\$.2f",$user->_balance_pending)?>
        </td>
        <td width="25%" valign="top">
            <label>Total Earnings</label>
            $<?=sprintf("%1\$.2f",$user->get_total_earnings())?>
        </td>
        <td width="25%" valign="top">
            <label>Total Paid Out</label>
            $<?=sprintf("%1\$.2f",$user->get_total_payout())?>    
        </td>
    </tr>
</table>
</div>

</div>

<div id="trans_panel" class="page">
    <table cellspacing="0" cellpadding="0"><tr>
        <td width="25%" class="first" valign="top">
            <h4>Top Artists</h4>
            <ul>
            
<?php foreach($artists as $i => $artist) { ?>
                <li><?=$artist->_display_name?></li>
<?php } ?>

            </ul>
        </td>
        <td width="25%" valign="top">
            <h4>Your Sawce</h4>
            <ul>

<?php foreach($sawce as $i => $song) { ?>
                <li><?=$song->_song_name?></li>
<?php } ?>            
            
            </ul>
        </td>
        <td width="25%" valign="top">
            <h4>Your Library</h4>
            <ul>
            
<?php foreach($library as $i => $song) { ?>
                <li><?=$song->_song_name?></li>
<?php } ?>

            </ul>
        </td>
        <td width="25%" valign="top">
        </td>
    </tr></table>    
</div>

<script>$('.stat').hover(function() { $(this).addClass('hover'); }, function() { $(this).removeClass('hover'); });</script>

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
* begin output buffer so we can grab the footer content
***********************************************************************/?>
<?php start_footer_content(); ?>

<?php load_component('messages'); ?>

<?php end_footer_content(); ?>