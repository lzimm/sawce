<?php
	global $u_profile;
	global $u_display;
	global $u_submit;
	global $user;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

	<form action="<?=build_link('my','edit')?>" method="post" class="makeform">
	
	<table class="makebasic" cellpadding="0" cellspacing="0">

	<tr><td>
	<label>Display Name</label><?=$u_display->build()?>
	</tr></td>
	
	<tr><td>
    <label>Websites (comma separated)</label><?=$u_profile->build();?>
	</td></tr>
	
	<tr><td>
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