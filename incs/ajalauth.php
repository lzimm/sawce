<?php
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

<lb_html><![CDATA[

<?php include('incs/ajalheader.php'); ?>
<div class="header">Authentication Required</div>
<div class="body">

<table class="makebasic" cellpadding="0" cellspacing="0"><tr><td valign="top" width="50%">   
    
	<h3>Login</h3>
	<form id="lb_login" action="<?=build_link_secure($page,$section,$id,$action,$key,$reqstring)?>" method="post" class="makeform">
	<table class="makebasic" width="100%" cellpadding="0" cellspacing="0">
		<tr><td colspan="2"><label>Email</label><?=$u_email->build();?></td></tr>
		<tr><td colspan="2"><label>Password</label><?=$u_pass->build();?></td></tr>
		<tr><td colspan="2"><label>Login</label><?=$u_submit->build("return lb_submit('lb_login');");?></td></tr>
	</table>
	</form>
            
</td><td valign="top" width="50%">

	<h3>Register</h3>
	<form id="lb_register" action="<?=build_link_secure($page,$section,$id,$action,$key,$reqstring)?>" method="post" class="makeform">
	<table class="makebasic" width="100%" cellpadding="0" cellspacing="0">
		<tr><td colspan="2"><label>Email</label><?=$r_email->build();?></td></tr>
		<tr><td colspan="2"><label>Username</label><?=$r_name->build();?></td></tr>
		<tr><td colspan="2"><label>Display Name</label><?=$r_display->build();?></td></tr>
		<tr><td><label>Password</label><?=$r_pass->build();?></td>
			<td><label>Verify Password</label><?=$r_pass_chk->build();?></td></tr>
		<tr><td colspan="2"><label>Type</label><?=$r_type->build();?></td></tr>
		<tr><td colspan="2"><?=$r_check->build();?><?=HTML_LABEL_AGREE_TERMS?></td></tr>
		<tr><td colspan="2"><label>Register</label><?=$r_submit->build("return lb_submit('lb_register');");?></td></tr>
	</table>
	</form>

</td></tr></table>

<div class="clear"></div>
</div>
<?php include('incs/ajalfooter.php'); ?>

]]></lb_html>
