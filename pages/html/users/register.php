<?php	
    global $u_name;
    global $u_pass;
    global $u_pass_chk;
    global $u_email;
    global $u_display;
    global $u_profile;
    global $u_type;
    global $u_check;
    global $u_submit;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="login_panel" class="page shdw_bot"><div class="padding">                            
<h3>Register</h3>
<form action="<?=build_link_secure('users','register')?>" method="post" class="makeform">
<table class="makebasic" cellpadding="0" cellspacing="0">
    <tr>
        <td width="50%"><label>Email</label><?=$u_email->build();?></td>
        <td width="50%"><label>Username</label><?=$u_name->build();?></td>
    </tr>
    <tr>
        <td width="50%"><label>Password</label><?=$u_pass->build();?></td>
        <td width="50%"><label>Verify Password</label><?=$u_pass_chk->build();?></td>
    </tr>
    <tr>
        <td width="50%"><label>Type</label><?=$u_type->build();?></td>
        <td width="50%"><label>Display Name</label><?=$u_display->build();?></td>
    </tr>
    <tr>
        <td table colspan="2"><?=$u_check->build();?><?=HTML_LABEL_AGREE_TERMS?></td>
    </tr>
    <tr>
        <td table colspan="2"><label>Register</label><?=$u_submit->build();?></td>
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
            <li class="first"><a href="<?=build_link('users','login')?>">Login</a></li>
            <li><a href="<?=build_link('users','password')?>">Lost Password</a></li> 
            <li><a href="<?=build_link('about','spread')?>">Learn More</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>