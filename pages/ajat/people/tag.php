<?php
    global $id;
    
    global $related_tags;
    global $related_spreaders;
?>

<div style="width: 625px; height: 30px; display: block;"><h2><?=$id?> <span>(<a href="<?=build_link('people','tag',$id)?>">more</a>)</span></h2>
	<a class="rbl" href="javascript:map_overlay_close();"><span>Close Tag</span></a>
	<div class="clear slim"></div>
</div>

<div style="width: 200px; padding-right: 10px; float: left;">
<div class="rc_tb rc_666 map_curves">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    	<h3>Related Tags</h3>
    	<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($related_tags as $i => $tag) { ?>
	
		<tr<?=($i==0)?" class='first_row'":"";?>>
		<td><a href="<?=build_link('people','tag',$tag['genre'])?>" onclick="return map_click(this);"><?=$tag['genre']?></a></td>
		</tr>

<?php } ?>

		</table>
	</div>
	<div class="rc_b"><div><div><div></div></div></div></div>
</div>
</div>

<div style="width: 410px; float: left;">
<div class="rc_tb rc_666 map_curves">
	<div class="rc_t"><div><div><div></div></div></div></div>
    <div class="rc_c">
    	<h3>Related Users</h3>
    	<table cellspacing="0" cellpadding="0" class="rowlines">

<?php foreach($related_spreaders as $i => $spreader) { ?>

			<tr<?=($i==0)?" class='first_row'":"";?>>
				<td width="30%">
					<?php if ($spreader['maven']) { ?>
						<a href="<?=build_link('people','profile',$spreader['maven'])?>"><?=$spreader['maven']?></a>
					<?php } else { ?>
						<span class="nobody_777">(nobody)</span>
					<?php } ?>
				</td>
				<td><img src="/_img/arrow_999_666.gif"></td>
				<td width="30%"><a href="<?=build_link('people','profile',$spreader['connector'])?>"><?=$spreader['connector']?></a></td>
				<td><img src="/_img/arrow_999_666.gif"></td>
				<td width="30%"><?=$spreader['count']?> others</td>
			</tr>

<?php } ?>

		</table>
	</div>
	<div class="rc_b"><div><div><div></div></div></div></div>
</div>
</div>