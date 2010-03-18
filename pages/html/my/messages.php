<?php
	global $user;
	global $messages;
	global $next;
	global $id;
	global $action;
	global $reqstring;
	global $message_header;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<h3>Messages</h3>
<table class="maketable messages rowlines" table>

<?php foreach($messages as $i => $message) { ?>

<tr<?=($i==0)?" class='first_row'":"";?>>
<td width="20%" class="first">
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

<?php load_global_component('paginate', array(	'next' => $next, 
												'prev' => ($id > 0),
												'nextlink' => build_link('my','messages',$id + 1,$action,$reqstring),
												'prevlink' => build_link('my','messages',$id - 1,$action,$reqstring))); ?>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">More</div>
    <div class="header">Messages</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('my','messages',0,'inbox')?>">Received</a> (<a href="<?=build_link('my','messages',0,'new')?>"><?=sizeof(Util::as_authed()->get_new_messages())?></a>)</li> 
            <li><a href="<?=build_link('my','messages',0,'sent')?>">Sent</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>