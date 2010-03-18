<?php

	global $u_email;
	global $u_pass;
	global $u_submit;

?>

<div class="padding">
<h1>Login</h1>
<p>Before you play, you need to give us your Sawce account so that we can attach our widget to your profile. Once that's done, you'll be able to spread great music to all your friends.<p>
<p>If you don't have an account, <a href="<?=HTTP_LINK_BASE?>" target="_blank">click here</a> to find out all about how you can Spread Sawce.</p>
</div>

<div id="login">

<?php Util::process_messages() ?>

<table class="makebasic" cellpadding="0" cellspacing="0">
	<tr><td width="40%"><label>Email</label><?=$u_email->build();?></td>
		<td width="40%"><label>Password</label><?=$u_pass->build();?></td>
		<td width="20%"><label>Login</label><input type="submit" value="Login" /></td></tr>
</table>
</div>