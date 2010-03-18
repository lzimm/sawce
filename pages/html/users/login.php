<?php	
	global $u_email;
    global $u_pass;
	global $u_submit;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="login_panel" class="page shdw_bot"><div class="padding">                            
<h3>Login</h3>
<p>Enter your email address and password to login.</p>
<form action="<?=build_link_secure('users','login')?>" method="post" class="makeform">
<table class="makebasic" cellpadding="0" cellspacing="0">
    <tr>
        <td width="33%"><label>Email</label><?=$u_email->build();?></td>
        <td width="33%"><label>Password</label><?=$u_pass->build();?></td>
        <td width="33%"><label>Login</label><?=$u_submit->build();?></td>
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
    <div class="right_top">Help</div>
    <div class="header">Tools</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('users','register')?>">Register</a></li>
            <li><a href="<?=build_link('users','password')?>">Lost Password</a></li> 
            <li><a href="<?=build_link('about','spread')?>">Learn More</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>