<?php
	global $status;
	global $u_status;
	global $u_submit;
?>
<sw_body><![CDATA[

<form action="<?=build_link('my','status');?>" method="post" class="makeform" id="form_status">
<?=$u_status->build();?><?=$u_submit->build();?>
</form>

]]></sw_body>
