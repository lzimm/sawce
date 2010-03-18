<?php
	global $id;
	$album = Album::find($id);
?>

<?php define_header_part(2, $album->_album_name); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="page_panel" class="page shdw_bot"><div class="padding">   
<table cellspacing="0" cellpadding="0" class="makebasic">
<tr><td width="99%" class="first" valign="top">

<label>How it Works</label><p>Embed this album wherever you can and make sales through it. Perfect for your website, MySpace, Facebook or other social profile.</p>

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

<div><label>Embed Code</label>
<input readonly="" type="text" value='<iframe src="<?=build_link('spread','sawce',$id,'album','version:1.0')?>" style="width: 100%; height: 100%; border: none;" hspace="0" vspace="0"></iframe>' class="field" id="embed_code" onclick="javascript:document.embedForm.embed_code.focus();document.embedForm.embed_code.select();" />
<input readonly="" type="text" value='<object table height="400"><param name="movie" value="<?=build_link('spread','player')?>/album.swf?album=<?=$id?>"></param><param name="SCALE" value="noscale" /><embed src="<?=build_link('spread','player')?>/album.swf?album=<?=$id?>" type="application/x-shockwave-flash" table height="400" scale="noscale"></embed></object>' class="field" id="swf_code" onclick="javascript:document.embedForm.swf_code.focus();document.embedForm.swf_code.select();" /></div>
	
</td><td valign="top" width="440">
	
<div style="height: 350px;">	
	<div id="embed_frame"><iframe src="<?=build_link('spread','sawce',$id,'album','version:1.0')?>" style="width: 440px; height: 350px; border: none;" hspace="0" vspace="0"></iframe></div>
       <div id="embed_swf">
           <object width="440" height="350">
               <param name="movie" value="<?=build_link('spread','player')?>/album.swf?album=<?=$id?>"></param> 
               <param name="SCALE" value="noscale" />
               <embed src="<?=build_link('spread','player')?>/album.swf?album=<?=$id?>" type="application/x-shockwave-flash" width="440" height="350" scale="noscale"></embed>
           </object>
       </div>
	<div id="embed_api" class="embed_info">
		<div><label>Simple Javascript API</label>
		<p>Our simple Javascript API allows you to build completely custom Sawce containers on the fly. Simply include our remote script and build your widget dynamically off of our JSON interface.</p>
		<p><a href="<?=build_link('embed','js',$id,'album')?>" class="learn_btn"><span>Learn More</span></a></p></div>
		
		<div><label>Full API</label>
		<p>Our Full API lets you build Sawce containers on your own terms. Simply make a request to our API to grab all the data you need and build whatever you need from the ground up.</p>
		<p><a href="<?=build_link('embed','api',$id,'album')?>" class="learn_btn"><span>Learn More</span></a></p></div>
	</div>
</div>
	
</td></tr>
</table>
</div></div>

<?=insert_sawce_footer();?> 

<script>
	widget_link_base = "<?=build_link('spread','sawce',$id,'album','version:1.0')?>";
    widget_swf_base = "<?=build_link('spread','player')?>/album.swf?album=<?=$id?>"; 
    
    $('#iframe_type').change(refresh_iframe);
	$('.iframe_changer').keyup(refresh_iframe);
    
    refresh_iframe();
</script>

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