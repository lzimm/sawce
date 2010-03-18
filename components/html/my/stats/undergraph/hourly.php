<?php
	global $period_total;
	global $date_select;
	global $date_submit;
?>

<table cellspacing="0" cellpadding="0" class="makebasic collines">
	<tr>
		<td class="first_col" width="50%" valign="top">
			<label>24 Hours Ending</label>
			<form action="<?=build_link('my','stats','hourly')?>" method="post" class="makeform">
			<table cellspacing="0" cellpadding="0" class="makebasic">
				<tr><td width="70%" class="first"><?=$date_select->build();?></td>
					<td width="30%" class="first"><?=$date_submit->build();?></td></tr>
			</table>
			</form>
		</td>
		<td width="50%" valign="top">
			<label>Total This Period</label>
			$<?=sprintf("%1\$.2f",$period_total)?>	
		</td>
	</tr>
</table>