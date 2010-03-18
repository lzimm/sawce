<?php
	global $user;
	global $status;
	global $u_status;
	global $u_submit;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<h3>Update Status</h3>

<form action="<?=build_link('my','status')?>" method="post" class="makeform">

<table class="makebasic" cellpadding="0" cellspacing="0">

<tr><td><label>Status</label>
<?=$u_status->build();?>
</td></tr>

<tr><td><label>Update</label>
<?=$u_submit->build();?>
</td></tr>

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