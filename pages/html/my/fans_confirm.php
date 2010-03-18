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
	
	global $hidden_subject;
	global $hidden_body;
	
	global $confirm_submit;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<h3>Spread a message to <?=$fan_count?> fans</h3>

<form action="<?=build_link('my', 'fans', 'connect', 'confirm')?>" method="post" class="makeform">

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="30%"><label>Subject</label><?=$hidden_subject->build()?></td>
		<td width="70%"><?=$subject->get()?></td>
	</tr>

	<tr>
		<td width="30%"><label>Message</label><?=$hidden_body->build()?></td>
		<td width="70%"><?=stripslashes($body->get())?></td>
	</tr>

	<tr>
		<td width="30%">&nbsp;</td>
		<td width="70%">&nbsp;</td>
	</tr>

	<tr>
		<td width="30%">&nbsp;</td>
		<td width="70%"><a href="<?=build_link('my','fans')?>" class="btn"><span>Cancel</span></a><?=$confirm_submit->build();?></td>
	</tr>
</table>

<?=$filtered->build();?>

</form>

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