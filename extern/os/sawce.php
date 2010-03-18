<?php
	
	global $user;
	$songs = $user->sawce_get();

	load_helper('color');
	
	$bg = (isset($req['bg']))?$req['bg']:'333333';
	$bghex = array(substr($bg, 0, 2), substr($bg, 2, 2), substr($bg, 4, 2));
	$bgrgb = array(hexdec($bghex[0]), hexdec($bghex[1]), hexdec($bghex[2]));
	
	function offset($offset, $pos = true) {
		global $bgrgb;
		$hsv = RGB_to_HSV($bgrgb[0], $bgrgb[1], $bgrgb[2]);

		$hsv[2] = $pos ? min(1, $hsv[2]+$offset): max(0, $hsv[2]-$offset);
		
		$rgb = HSV_to_RGB($hsv[0], $hsv[1], $hsv[2]);
		
		return 	(strlen(dechex($rgb[0])) > 1 ? dechex($rgb[0]) : '0' . dechex($rgb[0])). 
				(strlen(dechex($rgb[1])) > 1 ? dechex($rgb[1]) : '0' . dechex($rgb[1])). 
				(strlen(dechex($rgb[2])) > 1 ? dechex($rgb[2]) : '0' . dechex($rgb[2]));
	}
	
?>

<style>
	* { margin: 0; padding: 0 }
	body { padding: 0px; }
	body { background-color: #<?=offset(0.0)?>; font-family: "Lucida Grande","Lucida Sans Unicode","Lucida Sans",Verdana,Arial,sans-serif; font-size: 11px; line-height: 18px; color: #<?=offset('66')?>; }

	a img { border: none; }

	#sawcehead { height: 24px; background-color: #<?=offset(0.1, false)?>; position: relative; }
	#sawcelogo { padding: 5px; position: absolute; top: 0; left: 0; width: 75px; background-color: #000; }
	#sawcebody { border: 5px solid #<?=offset(0.1, false)?>; }

	div.song { border-top: 5px solid #<?=offset(0.1, false)?>; padding: 0; }
	div.song_hover { background-color: #<?=offset(0.3)?>; cursor: pointer; }
	div.song_first { border-top: none; }
	div.song div.album { display: none; }
	div.song div.info { padding: 5px; }
	div.song div.info span.name { font-size: 12px; color: #<?=offset(0.9)?>; }
	div.song div.info span.name span { display: none; }
	div.song div.info span.details { }
	div.song div.info span.details span { display: none; }
	div.song div.controls { display: none; }
	div.song div.controls a { color: #<?=offset(0.9)?>; text-decoration: none; float: left; display: block; font-weight: bold; font-size: 10px; line-height: 10px; padding: 3px 10px; margin-right: 1px; background-color: #<?=offset(0.1, false)?>; }
	div.song div.controls a:hover { background-color: #<?=offset(0.3)?>; }

	div.song_selected div.album { float: left; margin-right: 5px; display: block; }
	div.song_selected div.info { margin-left: 55px; }
	div.song_selected div.info span.name { font-size: 12px; color: #<?=offset(0.9)?>; }
	div.song_selected div.info span.name span { display: inline; }
	div.song_selected div.info span.details { display: block; border-top: 1px solid #<?=offset(0.1, false)?>; }
	div.song_selected div.info span.details span { display: inline; }
	div.song_selected div.controls { clear: both; border-top: 5px solid #<?=offset(0.1, false)?>; display: block; background-color: #<?=offset(0.1, false)?>; }

	div.clear { clear: both; }
</style>

<?php if (isset($req['css'])) { ?>
<link href="http://<?=$req['css']?>" rel="stylesheet" type="text/css" />
<?php } ?>

<script src="jquery.js" type="text/javascript"></script>
<script src="flash_control.js" type="text/javascript"></script>
<script src="AC_RunActiveContent.js" type="text/javascript"></script>

<div id="sawcehead">
	<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','100%','height','24','src','/spread/single/track','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','/spread/single/track','name','sw_player','id','sw_player','swliveconnect','true','bgcolor','#222222','wmode','transparent' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" table height="24" id="sw_player">
	<param name="movie" value="/spread/single/track.swf" />
	<param name="quality" value="high" /><param name="BGCOLOR" value="#222222" />
	<param name="wmode" value="transparent">
	<embed src="/spread/single/track.swf" table height="24" name="sw_player" swliveconnect="true" quality="high" wmode="transparent" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" bgcolor="#222222"></embed>
</object></noscript>
	<div id="sawcelogo"><img src="/spread/assets/sawce_logo.png"></div>
</div>

<div id="sawcebody">

<?php foreach($songs as $i => $song) { ?>
	
<div class="song<?=$i==0?' song_first':''?>">
	<div class="album">
	
<?php
	if ($song->_album_art == 'none') {
		echo "<a href='" . build_link('music','album',$song->_album) ."' target='_blank'><img src='http://sawceart.s3.amazonaws.com/default-50.gif' /></a>";
	} else {
		echo "<a href='" . build_link('music','album',$song->_album) ."' target='_blank'><img src='http://sawceart.s3.amazonaws.com/" . $song->_artist . "/" . $song->_album . "-50." . $song->_album_art . "' /></a>";
	}
?>

	</div>
	<div class="info">
		<span class="name"><?=$song->_song_name?> <span>(<?=$song->_album_name?>)</span></span>
		<span class="details"><?=$song->_display_name?> <span>is <?=$song->_artist_status?></span></span>
	</div>
	<div class="controls">
		<a id="play_<?=$song->_id?>" href="javascript:set_track(<?=$song->_artist?>,<?=$song->_id?>);"><span>Play</span></a>
		<a id="stop_<?=$song->_id?>" href="javascript:stop_track(<?=$song->_id?>);"><span>Stop</span></a>
		<a href="<?=build_link('music','buy',$song->_id,'via',$ref)?>" target="_blank" onclick="return sw_buy(<?=$song->_id?>);" id="buy_<?=$song->_id?>" ><span>Buy</span></a>
	</div>
	<div class="clear"></div>
	<input type="hidden" class="artist_id" value="<?=$song->_artist?>" />
	<input type="hidden" class="song_id" value="<?=$song->_id?>" />
</div>

<?php } ?>

</div>

<script>
	var global_selected = 0;
	
	$('.song').hover(
		function() {
			$(this).addClass('song_hover');
		},
		
		function() {
			$(this).removeClass('song_hover');
		});
	
	$('.song').click(
		function() {
			var this_artist = $(this).find('.artist_id').val();
			var this_song = $(this).find('.song_id').val();
			
			if (global_selected != this_song) {
				$('.song_selected').removeClass('song_selected');
				$(this).addClass('song_selected');
				set_track(this_artist, this_song);
				global_selected = $(this).find('.song_id').val();
			} else {
				$(this).removeClass('song_selected');
				stop_track(this_song);
				global_selected = 0;
			}
		});
</script>