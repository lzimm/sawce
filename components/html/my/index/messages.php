<?php
	global $messages;
?>

<div id="foot_panel" class="messages"><div class="header">Messages</div>
    <div class="body">
		<table>

<?php foreach($messages as $i => $message) { ?>
	
		<tr<?=($i==0)?" class='first'":"";?>>
		<td width="20%">
			<?=$message->_read ? '' : '<strong>'?>
			<a href="<?=build_link('people','profile',$message->_from_user)?>"><?=$message->_from_user?></a>
			<?=$message->_read ? '' : '</strong>'?>
		</td>
		<td width="80%">
			<?=$message->_read ? '' : '<strong>'?>
			<a href="<?=build_link('my','message',$message->_id)?>"><?=$message->_subject?></a>
			<?=$message->_read ? '' : '</strong>'?>
		</td>
		</tr>

<?php } ?>

		</table>
    </div>
</div>