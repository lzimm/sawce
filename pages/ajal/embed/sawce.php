<?php
	global $id;
?>

<lb_width>650</lb_width>
<lb_height>400</lb_height>
<lb_script><![CDATA[                                                                  
	widget_link_base = "<?=build_link('spread','sawce',$id,'version:1.0')?>";
    widget_swf_base = "<?=build_link('spread','player')?>/sawce.swf?user=<?=$id?>";
	
    $('#iframe_type').change(refresh_iframe);
    $('.iframe_changer').keyup(refresh_iframe);
    
    refresh_iframe();
]]></lb_script>
<lb_html><![CDATA[

<?php include('incs/ajalheader.php'); ?>

<form class="makeform" name="embedForm">
<h2>Embed and Spread</h2>
<table cellspacing="0" cellpadding="0" border="0" table class="makebasic">
	<tr>
		<td valign="top" width="50%" style="height: 350px;">
            <div id="embed_frame"><iframe src="<?=build_link('spread','sawce',$id,'version:1.0')?>" style="width: 250px; height: 350px; border: 3px solid #777;" hspace="0" vspace="0"></iframe></div>
            <div id="embed_swf">
                <object width="250" height="350">
                    <param name="movie" value="<?=build_link('spread','player')?>/sawce.swf?user=<?=$id?>"></param> 
                    <param name="SCALE" value="noscale" />
                    <embed src="<?=build_link('spread','player')?>/sawce.swf?user=<?=$id?>" type="application/x-shockwave-flash" width="250" height="350" scale="noscale"></embed>
                </object>
            </div>
			<div id="embed_api" class="embed_info">
				<div><label>Simple Javascript API</label>
				<p>Our simple Javascript API allows you to build completely custom Sawce containers on the fly. Simply include our remote script and build your widget dynamically off of our JSON interface.</p>
				<p><a href="<?=build_link('embed','js',$id)?>" class="><span>Learn More</span></a></p></div>
			
				<div><label>Full API</label>
				<p>Our Full API lets you build Sawce containers on your own terms. Simply make a request to our API to grab all the data you need and build whatever you need from the ground up.</p>
				<p><a href="<?=build_link('embed','api',$id)?>" class="><span>Learn More</span></a></p></div>
			</div>
        </td>
		<td valign="top" width="50%">
			<label>How it Works</label><p>Your Sawce is a special collection of your favorite songs. Paste this widget anywhere you can, and if people buy songs from you, you'll earn a commission for making the sale and gain a reputation as part of the distribution graph.</p>
            <div><label>Type</label><select id="iframe_type"><option value="swf" selected="true">Embedded Flash</option><option value="iframe">Customizable Embedded Frame</option><option value="api">API Integration Options</option></select></div>

            <div id="embed_size" style="padding: 0; margin: 0;">
                <table cellspacing="0" cellpadding="0" border="0"><tr><td width="50%" style="padding-right: 10px;"><div>
                    <label>Width</label><input type="text" value="100%" class="field iframe_changer" id="embed_width" />
                </div></td><td width="50%" style="padding-left: 10px;"><div>
                    <label>Height</label><input type="text" value="350" class="field iframe_changer" id="embed_height" />
                </div></td></tr></table>
            </div>

            <div id="frame_options">
                <div id="field_color"><label>Background Color</label><input type="text" value="#333333" class="field iframe_changer" id="iframe_color" /></div>
                <div id="field_css"><label>Style Sheet</label><input type="text" value="" class="field iframe_changer" id="iframe_css" /></div>
            </div>
		</td>
	</tr>
	<tr><td colspan="2">
        <label>Embed Code</label>
            <input readonly="" style="width: 580px;" type="text" value='<iframe src="<?=build_link('spread','sawce',$id,'version:1.0')?>" style="width: 100%; height: 100%; border: none;" hspace="0" vspace="0"></iframe>' class="field" id="embed_code" onclick="javascript:document.embedForm.embed_code.focus();document.embedForm.embed_code.select();" />
            <input readonly="" style="width: 580px;" type="text" value='<object table height="400"><param name="movie" value="<?=build_link('spread','player')?>/sawce.swf?user=<?=$id?>"></param><param name="SCALE" value="noscale" /><embed src="<?=build_link('spread','player')?>/sawce.swf?user=<?=$id?>" type="application/x-shockwave-flash" table height="400" scale="noscale"></embed></object>' class="field" id="swf_code" onclick="javascript:document.embedForm.swf_code.focus();document.embedForm.swf_code.select();" />   
    </td></tr>
</table>
</form>

<?php include('incs/ajalfooter.php'); ?>

]]></lb_html>
