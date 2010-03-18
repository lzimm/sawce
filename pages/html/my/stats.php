<?php
	global $id;
	global $user;
	
	global $earnings;
	global $stats;
	global $start_time;
	
	global $set_size;
	global $date_string;
	global $sec_diff;
	global $bar_count;

?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="my_panel">

<div id="earnings_graph">

<?php 
	$earnings_max = $earnings[sizeof($earnings) - 1]['max'];
	$index = sizeof($earnings) - 2;
	
	for ($i = 0; $i < $set_size; $i++) { 
		$date = date($date_string, $start_time - $sec_diff*(($set_size-1)-$i));
		if (($index >= 0) && (date($date_string, strtotime($earnings[$index]['time'])) == $date)) {
			$earned = $earnings[$index]['sum'];
			$height = $earnings[$index]['sum']/$earnings_max*200 . 'px';
			$index--;
		} else {
			$earned = 0;
			$height = '5px';
		}
?>

<div class="stat" style="left: <?=$i*(100/$set_size);?>%; width: <?=(100/$set_size)?>%; height: <?=$height?>;"> 
    <div class="stats">$<?=sprintf("%1\$.2f", $earned)?> (<?=$date?>)</div>
</div>

<?php } ?>

	<div class="options">
		<a href="<?=build_link('my','stats','hourly')?>">Hourly</a>
		<a href="<?=build_link('my','stats','daily')?>">Daily</a>
		<a href="<?=build_link('my','stats','monthly')?>">Monthly</a>
	</div>
</div>

<div id="earnings_sub">

<?php load_component('undergraph', $id); ?>

</div>

</div>

<div id="trans_panel">
	<table class="makebasic rowlines" cellspacing="0" cellpadding="0">
		<tr><td width="45%" class="first"><h4>Song</h4></td><td width="20%"><h4>User</h4></td><td width="25%"><h4>Time</h4></td><td width="10%"><h4>Value</h4></td></tr>
		
<?php foreach($stats as $i => $stat) { ?>

		<tr>
			<td class="first"><a href="<?=build_link('my','song',$stat['song'])?>"><?=$stat['song_name']?></a></td>
			<td><a href="<?=build_link('people','profile',$stat['username'])?>"><?=$stat['username']?></a></td>
			<td><?=$stat['time']?></td>
			<td>$<?=sprintf("%1\$.2f",$stat['value'])?></td>
		</tr>

<?php } ?>

	</table>
</div>

<script>$('.stat').hover(function() { $(this).addClass('hover'); }, function() { $(this).removeClass('hover'); });</script>

<?php end_focus_content(); ?>