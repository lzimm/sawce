<?php
	global $id;
	global $action;
?>

<?php define_header(); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   

<h3>Coming Soon</h3>
<p>Please use Simple Javascript API while the Full API is finalized.</p>

</div></div>

<div id="trans_panel" class="page">
	
</div>

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