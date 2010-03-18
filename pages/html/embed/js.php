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

<h3>Getting Started</h3>
<p>The first step in integrating with our Simple Javascript API is copying and pasting the following code directly into your webpage (Note: Though direct API integration is the best way to completely control the look and feel of your Sawce container, you can only use it where allowed to implement custom Javascript, and thus many social profiles may not allow you to do so).</p>

<form name="codeContainer" class="makeform" style="margin-bottom: 30px;">
	<textarea rows="6" readonly="" onclick="javascript:document.codeContainer.code.focus();document.codeContainer.code.select();" name="code"><object width="1" height="1" id="sawce_player"><param name="movie" value="http://delta.sawce.net/spread/js/track.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent"><param name="allowScriptAccess" value="always" /><embed src="http://delta.sawce.net/spread/js/track.swf" allowScriptAccess="always" width="1" height="1" name="sawce_player" swliveconnect="true" quality="high" wmode="transparent" type="application/x-shockwave-flash"></embed></object>
<div id="sawce_container"></div>
<script type="text/javascript" src="<?=build_link('spread','js',$id,$action)?>"></script>
<script type="text/javascript">sawce.init();</script></textarea>
</form>

<h3>Customizing Your Widget</h3>
<p>Once you've included the above code, you will then have access to a Javascript object called "sawce" on your page. The sawce object contains your songs in an array at sawce.songs. From there you can easily build up any UI components you want. If you want to play a song, for instance, the very first song in your collection, simply call sawce.songs[0].play().<p>

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