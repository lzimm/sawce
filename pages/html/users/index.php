<?php	
    global $req;
    
    global $u_email;
    global $u_pass;
    global $u_submit;

    global $r_name;
    global $r_pass;
    global $r_pass_chk;
    global $r_email;
    global $r_display;
    global $r_profile;
    global $r_type;
    global $r_check;
    global $r_submit;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="login_panel" class="page shdw_bot">
<table cellspacing="0" cellpadding="0" border="0"><tr><td width="50%" valign="top" class="first">
<h3>Login</h3>
<form action="<?=build_link_secure('users','login',(isset($req['pg'])?'pg:'.$req['pg']:''))?>" method="post" class="makeform">
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td><label>Email</label><?=$u_email->build();?></td></tr>
        <tr><td><label>Password</label><?=$u_pass->build();?></td></tr>
        <tr><td><label>Login</label><?=$u_submit->build();?></td></tr>
    </table>
</form>
</td><td width="50%" valign="top">
<h3>Register</h3>
<form action="<?=build_link_secure('users','register',(isset($req['pg'])?'pg:'.$req['pg']:''))?>" method="post" class="makeform">
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td><label>Email</label><?=$r_email->build();?></td></tr>
        <tr><td><label>Username</label><?=$r_name->build();?></td></tr>
        <tr><td><label>Display Name</label><?=$r_display->build();?></td></tr>
        <tr><td><label>Password</label><?=$r_pass->build();?></td></tr>
        <tr><td><label>Verify Password</label><?=$r_pass_chk->build();?></td></tr>
        <tr><td><label>Type</label><?=$r_type->build();?></td></tr>
        <tr><td><?=$r_check->build();?><?=HTML_LABEL_AGREE_TERMS?></tr></td>
        <tr><td><label>Register</label><?=$r_submit->build();?></td></tr>
    </table>
</form>
</td></tr></table>
</div>

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
            <li class="first"><a href="<?=build_link('users','password')?>">Lost Password</a></li> 
            <li><a href="<?=build_link('about','spread')?>">Learn More</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>