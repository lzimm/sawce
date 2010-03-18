<?php define_header_part(1, 'Privacy Policy'); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="about_panel" class="page shdw_bot"><div class="padding">                            
<h3>Privacy Policy</h3>

<table cellspacing="0" cellpadding="0" border="0" class="padless"><tr><td width="240" valign="top" class="first">
	<ul class="peripheral">
        <li class="first">Our privacy policy is designed to let you know what information we collect from you, how that information is used, and how can you tailor your Sawce experience to your personal preferences. For complete details, please see our <a href="<?=build_link('about','terms')?>">Terms and Conditions</a>.</li>
    </ul>

<td><td valign="top">

<h4>What Information We Collect</h4>
<p>We store information that we collect through cookies, log files, and other server side technologies to create a profile of your preferences and sales activity. Personally identifiable information is used by Sawce in order to provide tailored services and to improve the content of the site for you and create a better understanding of how you fit into the distribution graph. Non-personally identifiable, group information may be shared or sold to Sawce partners.</p>

<br /><br />

<h4>Who We Share Your Information With</h4>
<p>It is necessary to share billing information with a payment processing company to bill you for your purchases. These companies do not retain, share, store or use personally identifiable information for any other purposes. We will NEVER charge you for any goods or services without your explicit consent.<p>

<p>Royalties will be paid through Paypal. Only the information necessary to complete these transactions will be shared<p>
    
<p>Sawce will comply with demands made by law enforcement or other governmental officials, in response to a verified request relating to a criminal investigation.</p>

<br /><br />

<h4>Who We Do Not Share Your Information With</h4>
<p>With the exception of payment processing companies as listed above, we absolutely do NOT share your personally identifiable information (email address, phone number, first or last name, street address, social security number, or billing information) with ANY third-party or partner unless you explicitly request that we do.<p>

</td></tr></table>

</div></div>

<?=insert_sawce_footer();?> 

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Info</div>
    <div class="header">Learn More</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('about')?>">About Sawce</a></li> 
            <li><a href="<?=build_link('about','spread')?>">How it Works</a></li>
            <li><a href="<?=build_link('about','contact')?>">Contact</a></li>
            <li><a href="<?=build_link('about','terms')?>">Terms and Conditions</a></li>
            <li><a href="<?=build_link('about','privacy')?>">Privacy Policy</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>