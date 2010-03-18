<?php
	global $albums;
	global $new_album;
	global $album_name;
	global $album_submit;
?>
<sw_ajax><![CDATA[
<table class="makebasic" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%"><label>Album Name</label><?=$album_name->build();?></td>
		<td width="50%"><label>Create</label><?=$album_submit->build();?></td>
	</tr>
</table>
]]></sw_ajax>
<sw_append to="<?=((sizeof($albums) % 7) == 1)?'sw_body':'album_container_'.(ceil(sizeof($albums)/7) - 1);?>"><![CDATA[

<?php if ($new_album) { ?>

<?=((sizeof($albums) % 7) == 1)?'<div class="column span-14 first last" id="album_container_'.floor(sizeof($albums)/7).'">':''?>

<div class="column span-2<?=((sizeof($albums) % 7) == 1)?' first_clear':''?><?=((sizeof($albums) % 7) == 0)?' last':''?>">
<div class="rc_tb rc_444 album_slot">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    
	<div class="art"><?php
		if ($new_album->_art == 'none') {
			echo "<a href='" . build_link('my','album',$new_album->_id) ."'><img src='http://sawceart.s3.amazonaws.com/default-50.gif' /></a>";
		} else {
			echo "<a href='" . build_link('my','album',$new_album->_id) ."'><img src='http://sawceart.s3.amazonaws.com/" . $new_album->_artist . "/" . $new_album->_id . "-50." . $new_album->_art . "' /></a>";
		}
	?></div>
	<div class="info">
		<div><a href="<?=build_link('my','album',$new_album->_id)?>" class="title"><?=$new_album->_album_name?> <span class="aux">(<?=sizeof($new_album->get_songs())?>)</span></a></div>
		<div><a href="<?=build_link('my','album',$new_album->_id)?>">Edit</a> | 
			<a href="<?=build_link('my','album',$new_album->_id,'delete')?>">Delete</a></div>
	</div>

    </div>
    <div class="rc_b"><div><div><div></div></div></div></div>
</div>
</div>	

<?=((sizeof($albums) % 7) == 1)?'</div>':''?>

<?php } ?>

]]></sw_append>
<sw_body><![CDATA[
<?php
	$count = 0;
	foreach($albums as $album) {
		$count++;
?>

<?=(($count % 7) == 1)?'<div class="column span-14 first last" id="album_container_'.floor($count/7).'">':''?>

<div class="column span-2<?=(($count % 7) == 1)?' first_clear':''?><?=(($count % 7) == 0)?' last':''?>">
<div class="rc_tb rc_444 album_slot">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    
	<div class="art"><?php
		if ($album->_art == 'none') {
			echo "<a href='" . build_link('my','album',$album->_id) ."'><img src='http://sawceart.s3.amazonaws.com/default-50.gif' /></a>";
		} else {
			echo "<a href='" . build_link('my','album',$album->_id) ."'><img src='http://sawceart.s3.amazonaws.com/" . $album->_artist . "/" . $album->_id . "-50." . $album->_art . "' /></a>";
		}
	?></div>
	<div class="info">
		<div><a href="<?=build_link('my','album',$album->_id)?>" class="title"><?=$album->_album_name?> <span class="aux">(<?=sizeof($album->get_songs())?>)</span></a></div>
		<div><a href="<?=build_link('my','album',$album->_id)?>">Edit</a> | 
			<a href="<?=build_link('my','album',$album->_id,'delete')?>">Delete</a></div>
	</div>

    </div>
    <div class="rc_b"><div><div><div></div></div></div></div>
</div>
</div>

<?=(($count % 7) == 0) || ($count == sizeof($albums))?'</div>':''?>

<?php
	}
?>

]]></sw_body>
