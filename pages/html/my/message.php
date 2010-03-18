<?php
	global $message;
	global $body;
	global $submit;
	global $thread;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<h3><a href="<?=build_link('people','profile',$message->_from_user)?>"><?=$message->_from_user?></a></h3>
<p><?=$message->_body?></p>

<?php foreach($thread as $i => $t_message) { ?>

<h3><a href="<?=build_link('people','profile',$t_message->_from_user)?>"><?=$t_message->_from_user?></a></h3>
<p><?=$t_message->_body?></p>
	
<?php } ?>

</div></div>

<div id="trans_panel" class="page">
	<form action="<?=build_link('my','message',$message->_id)?>" method="post" class="makeform">
	
	<table class="makebasic">

	<tr><td class="first"><h4>Reply</h4></td></tr>

	<tr><td class="first"><label>Message</label>
	<?=$body->build();?>
	</td></tr>
	
	<tr><td class="first"><label>Reply</label>
	<?=$submit->build();?>
	</td></tr>
	
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
    <div class="header">Account Management</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('my','balance')?>">My Balance</a></li> 
            <li><a href="<?=build_link('my','withdraw')?>">Withdraw Funds</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>