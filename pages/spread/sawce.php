<?php
	global $ref;
	global $songs;
	global $req;
    global $spread_link;
	
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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sawce > Spread Music</title>
<link rel="shortcut icon" href="/favicon.ico" />
<style>
	* { margin: 0; padding: 0 }
	body { padding: 0px; }
	body { background-color: #<?=offset(0.0)?>; font-family: "Lucida Grande","Lucida Sans Unicode","Lucida Sans",Verdana,Arial,sans-serif; font-size: 11px; line-height: 18px; color: #<?=offset('66')?>; }

	a img { border: none; }

	#sawcehead { height: 24px; background-color: #<?=offset(0.1, false)?>; position: relative; }
	#sawcelogo { position: absolute; top: 0; left: 0; width: 75px; }
    #sawcelogo a { background-color: #000; padding: 5px; display: block; }
    #sawcelogo a:hover { background-color: #333; }
	#sawcebody { border: 5px solid #<?=offset(0.1, false)?>; }
    
    #cart { position: absolute; top: 0; left: 0; line-height: 14px; display: block; width: 100%; }
    #cart a { margin-left: 75px; color: #<?=offset(0.9)?>; font-weight: bold; text-decoration: none; padding: 5px; background-color: #<?=offset(0.5)?>; display: block; opacity: 0.50; filter: alpha(opacity=50); }
    #cart a:hover { opacity: 0.75; filter: alpha(opacity=75); background-color: #<?=offset(0.9)?>; color: #<?=offset(0.5)?>; }
    
    #spread { font-weight: bold; font-size: 9px; text-transform: uppercase; letter-spacing: 7px; text-align: center; display: none; }
    #spread a { color: #666; text-decoration: none; padding: 5px; background-color: #000; display: block; }
    #spread a:hover { color: #fff; }
    
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
    
    div.in_cart { background-color: #<?=offset(0.15)?>; }
    
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

<script src="/spread/assets/jquery.js" type="text/javascript"></script>
<script src="/spread/assets/flash_control.js" type="text/javascript"></script>
<script src="/js/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>

<div id="sawcehead">
	<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','100%','height','24','src','/spread/single/track','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','/spread/single/track','name','sw_player','id','sw_player','swliveconnect','true','bgcolor','#222222','wmode','transparent' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" table height="24" id="sw_player">
	<param name="movie" value="/spread/single/track.swf" />
	<param name="quality" value="high" /><param name="BGCOLOR" value="#222222" />
	<param name="wmode" value="transparent">
	<embed src="/spread/single/track.swf" table height="24" name="sw_player" swliveconnect="true" quality="high" wmode="transparent" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" bgcolor="#222222"></embed>
</object></noscript>
	<div id="sawcelogo"><a href="<?=$spread_link?>" target="_blank"><img src="/spread/assets/sawce_logo.png" border="0"></a></div>
    <div id="cart"><a id="checkout" href="#" target="_blank">Checkout</a></div>
</div>

<div id="spread"><a href="<?=$spread_link?>">Spread Sawce</a></div>

<div id="sawcebody">

<?php foreach($songs as $i => $song) { ?>
	
<div class="song<?=$i==0?' song_first':''?>" id="song_<?=$song->_id?>">
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
		<a href="<?=build_link('music','buy',$song->_id,'via',$ref)?>" target="_blank" onclick="return sw_buy(<?=$song->_id?>);" id="buy_<?=$song->_id?>" ><span class="cart_part">Add to Cart</span></a>
	</div>
	<div class="clear"></div>
	<input type="hidden" class="artist_id" value="<?=$song->_artist?>" />
	<input type="hidden" class="song_id" value="<?=$song->_id?>" />
</div>

<?php } ?>

</div>

</body>

<script>
    var global_selected = 0;

    $('.song').hover(
	    function() {
		    $(this).addClass('song_hover');
	    },
	    
	    function() {
		    $(this).removeClass('song_hover');
	    });

    var click_func = function(e) {
            e.target.blur();
            
            var this_artist = $(this).find('.artist_id').val();
            var this_song = $(this).find('.song_id').val();
            
            if (global_selected != this_song) {
                $('.song_selected').removeClass('song_selected');
                $(this).addClass('song_selected');
                set_track(this_artist, this_song);
                global_selected = $(this).find('.song_id').val();
            } else {
                if (e.target.className != 'cart_part') {
                    $(this).removeClass('song_selected');
                    stop_track(this_song);
                    global_selected = 0;
                }
            }
        }
            
    $('.song').click(click_func);
 
    var cart_base = "<?=build_link('music','pack')?>/";
    var via_part = "/via/<?=$ref?>";    
    function update_cart() {
        if (cart.length) {
            var ids = '';
            
            for (var i = 0; i < cart.length; i++) {
                ids += cart[i] + ',';
            }
            
            ids = ids.substring(0, ids.length - 1);
            
            var link = cart_base + ids + via_part;
            $('#checkout').attr('href', link);
            $('#cart').show();
        } else {
            $('#cart').hide();
        }
    }
    
    var cart = new Array();
    function sw_buy(id) {
        var inside = false;
        var tcart = new Array();
        for (var i = 0; i < cart.length; i++) {
            if (cart[i] == id) {
                inside = true;
            } else {
                tcart.push(cart[i]);
            }
        }
        if (inside) {
            cart = tcart;
            $('#buy_' + id + ' > span').html('Add to Cart');
            
            $('#song_' + id).removeClass('in_cart');
        } else {
            cart.push(id);
            $('#buy_' + id + ' > span').html('Remove from Cart');
            
            $('#song_' + id).addClass('in_cart');
        }
        
        update_cart();
        
        $('#song_' + id).click(function() {
                    $('#song_' + id).click(click_func);  
                }); 
                
        return false;
    }
    
    update_cart();
</script>

</html>