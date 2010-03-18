<?php
	global $id;
	global $req;
	global $reqstring;
	global $user;
	global $credits;
	global $submit;
    global $min;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">                            
<h3>My Balance</h3>

<form action="<?=build_link_secure('checkout',$reqstring)?>" method="post" class="makeform" id="sw_ajax">

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="30%"><label>Current Balance</label>$<?=$user->_balance;?></td>
		<td width="30%"><label>Required</label>$<?=$min;?></td>
		<td width="40%"><label>Purchase Credits</label><?=$credits->build();?></td>
	</tr>
	
	<tr>
		<td width="30%">&nbsp;</td>
		<td width="30%">&nbsp;</td>
		<td width="40%">&nbsp;</td>
	</tr>
	
	<tr>
		<td width="30%">&nbsp;</td>
		<td width="30%"><label>Proceed</label></td>
		<td width="40%"><?=$submit->build();?></td>
	</tr>
</table>

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