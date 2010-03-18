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
<h3>Checkout</h3>

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="50%">Credits To Purchase</td>
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
</table>

<h3>Billing Information</h3>
<form action="<?=build_link_secure('checkout','confirm',$reqstring)?>" method="post" class="makeform">

<table class="makebasic rowlines" table cellpadding="0" cellspacing="0">
	<tr class="first_row">
		<td width="50%"><label>First Name</label><?=$f_name->build();?></td>
		<td width="50%" colspan="2"><label>Card Type</label><?=$card_type->build();?></td>
	</tr>
	<tr>
		<td width="50%"><label>Last Name</label><?=$l_name->build();?></td>
		<td width="50%" colspan="2"><label>Card Number</label><?=$card_num->build();?></td>
	</tr>
	<tr>
		<td width="50%"><label>Address</label><?=$addr_1->build();?></td>
		<td width="25%"><label>Month</label><?=$card_mnth->build();?></td>
        <td width="25%"><label>Year</label><?=$card_year->build();?></td>
	</tr>
	<tr>
		<td width="50%"><label>Address Line 2</label><?=$addr_2->build();?></td>
		<td width="50%" colspan="2"><?=$submit->build();?></td>
	</tr>
	<tr>
		<td width="50%"><label>City</label><?=$city->build();?></td>
		<td width="50%" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="50%"><label>Province</label><?=$province->build();?></td>
		<td width="50%" colspan="2">&nbsp;</td>
	</tr>
    <tr>
        <td width="50%"><label>Postal Code</label><?=$postal->build();?></td>
        <td width="50%" colspan="2">&nbsp;</td>
    </tr>
	<tr>
		<td width="50%"><label>Country</label><?=$country->build();?></td>
		<td width="50%" colspan="2">&nbsp;</td>
	</tr>
</table>

<?=$credits->build();?>

</form>

</div></div>

<?=insert_sawce_footer();?>

<?php end_focus_content(); ?>