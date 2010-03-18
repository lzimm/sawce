<?php define_header_part(1, 'Contact Information'); ?> 
<?/***********************************************************************    
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="about_panel" class="page shdw_bot"><div class="padding">                            
<table cellspacing="0" cellpadding="0" border="0"><tr><td width="50%" valign="top" class="first">
<h3>Digital</h3>

<p>General: <a href="mailto:spread@sawce.net">spread@sawce.net</a><br />
Media: <a href="mailto:media@sawce.net">media@sawce.net</a><br />
Support: <a href="mailto:support@sawce.net">support@sawce.net</a><br />
Legal: <a href="mailto:legal@sawce.net">legal@sawce.net</a><br /></p>

</td><td width="50%" valign="top">
<h3>Mailing</h3>

<p>410-1275 Hamilton St. <br />Vancouver, BC <br />V6B 1E2 <br />Canada</p>

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