<?php

	global $u_email;
	global $u_pass;
	global $u_submit;

	$u_email	= new TextValidator('email', array('max_len' => 128), new EmailValidationStrategy());
	$u_pass 	= new TextValidator('pass', array('max_len' => 64, 'password' => TRUE));
	$u_submit	= new SubmitValidator('req_auth', array('label' => 'Login', 'nojs' => true));

	if ($_POST && isset($_POST['email'])) {
		try {
			if ($user = User::auth($u_email->get(), $u_pass->get())) {
				$user->set_fb($fb_user);
			} else {
				Util::user_error(ERR_LOGIN_FAILED);
			}
		} catch (Exception $e) {
			Util::catch_exception($e);
		}
	}
	
	if (!$user) {

?>

<div class="padding">
<h1>Login</h1>
<p>Before you play, you need to give us your Sawce account so that we can attach our widget to your profile. Once that's done, you'll be able to spread great music to all your friends.<p>
<p>If you don't have an account, <a href="<?=HTTP_LINK_BASE?>" target="_blank">click here</a> to find out all about how you can Spread Sawce.</p>
</div>

<div id="login">

<?php Util::process_messages() ?>

<form action="index.php" method="post" class="makeform">
<table class="makebasic" cellpadding="0" cellspacing="0">
	<tr><td width="40%"><label>Email</label><?=$u_email->build();?></td>
		<td width="40%"><label>Password</label><?=$u_pass->build();?></td>
		<td width="20%"><label>Login</label><input type="submit" value="Login" /></td></tr>
</table>
</form>
</div>

<?php

	}

?>