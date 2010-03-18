<?php
	global $id;
	global $req;
	global $reqstring;
	global $user;
	global $credits;

	global $f_name;
	global $l_name;
	global $addr_1;
	global $addr_2;
	global $city;
	global $province;
    global $postal;
	global $country;
	
	global $card_type;
	global $card_num;
	global $card_mnth;
    global $card_year;

	global $submit;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">                            
<h3>Receipt</h3>

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="50%">Credits Purchased</td>
		<td width="50%">$<?=$credits->get();?></td>
	</tr>
    <tr>
        <td width="50%">Transaction Fee</td>
        <td width="50%">$<?=$GLOBALS['cfg']['fees']['transaction'];?></td>
    </tr>
    <tr>
        <td width="50%">Tax</td>
        <td width="50%">$<?=sprintf("%1\$.2f", Util::compute_taxes($GLOBALS['cfg']['fees']['transaction']+$credits->get()));?></td>
    </tr>
    <tr class="total_line">
        <td width="50%">Total</td>
        <td width="50%">$<?=sprintf("%1\$.2f", Util::compute_taxes($GLOBALS['cfg']['fees']['transaction']+$credits->get(),1));?></td>
    </tr>
    <tr class="total_line">
        <td width="50%">Current Balance</td>
        <td width="50%">$<?=sprintf("%1\$.2f", $user->_balance);?></td>
    </tr>
</table>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>