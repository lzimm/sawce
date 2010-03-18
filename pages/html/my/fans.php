<?php
	global $user;
	global $subject;
	global $body;
	global $submit;
	global $fans;
	global $filter;
	global $filtered;
	global $filter_submit;
	global $fan_count;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

	<h3>Important Fans</h3>
	<table cellspacing="0" cellpadding="0" class="rowlines">
		<tr>
			<td width="30%" class="first">This user spread to</td>
			<td width="40%">Who spread to<td>
			<td width="20%">This many</td>
		</tr>

<?php foreach($user->get_spreaders() as $i => $spreader) { ?>

		<tr>
			<td width="30%" class="first"><a href="<?=build_link('people','profile',$spreader['maven'])?>"><?=$spreader['maven']?></a></td>
			<td width="40%"><a href="<?=build_link('people','profile',$spreader['connector'])?>"><?=$spreader['connector']?></a></td>
			<td width="20%"><?=$spreader['count']?> others</td>
		</tr>

<?php } ?>

	</table>
	
	<h3>Your Fans</h3>
	<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($fans as $i => $fan) { ?>

		<tr<?=($i==0)?" class='first_row'":"";?>>
			<td width="50%" class="first"><a href="<?=build_link('people','profile',$fan['user']->_username)?>"><?=$fan['user']->_username?></a></td>
			<td width="25%">bought <strong><?=$fan['count']?></strong> songs</td>
			<td width="25%">spread to <strong><?=$fan['spread']?></strong></td>
		</tr>

<?php } ?>

	</table>

</div></div>

<div id="trans_panel" class="page">
	
	<form action="<?=build_link('my','fans')?>" method="post" class="makeform">
			
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="40%" class="first"><h4>Filter Your Fans</h4></td>
				<td width="15%"><h4>Most Recent Purchase</h4></td>
				<td width="15%"><h4>Release to Purchase Lag</h4></td>
				<td width="15%"><h4>Songs Bought</h4></td>
				<td width="15%"><h4>Spread To</h4></td>
			</tr>
			<tr>
				<td class="first" rowspan="2"><p>To meaningfully reach your fans, filter them based on when they bought their last song, the number of songs they loved, or the number of people they spread you to.</p></td>
				<td><label>After</label><?=$filter['date_min']->build();?></td>
				<td><label>At Least</label><?=$filter['adoption_min']->build();?></td>
				<td><label>At Least</label><?=$filter['songs_min']->build();?></td>
				<td><label>At Least</label><?=$filter['spread_min']->build();?></td>
			</tr>
			<tr>
				<td><label>Before</label><?=$filter['date_max']->build();?></td>
				<td><label>At Most</label><?=$filter['adoption_max']->build();?></td>
				<td><label>At Most</label><?=$filter['songs_max']->build();?></td>
				<td><label>At Most</label><?=$filter['spread_max']->build();?></td>
			</tr>
			<tr><td colspan="5" class="first" align="right"><?=$filter_submit->build();?></td></tr>
		</table>
		
	</form>

</div>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">More</div>
    <div class="header">Send a message to the <?=$fan_count?> fans you have selected.</div>
    <div class="section">
		<form action="<?=build_link('my','fans','connect')?>" method="post" class="makeform">
		<table class="makebasic">
			<tr><td><label>Subject</label><?=$subject->build();?></td></tr>
			<tr><td><label>Message</label><?=$body->build();?></td></tr>
			<tr><td><label>Send</label><?=$submit->build();?></td></tr>
		</table>
		<?=$filtered->build();?>
		</form>
    </div>
</div></div>

<?php end_peripheral_content(); ?>