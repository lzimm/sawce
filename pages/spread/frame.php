<?php
	global $ref;
	global $songs;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sawce > Spread Music</title>
<link rel="shortcut icon" href="/favicon.ico" />
<link href="/spread/assets/frame.css" rel="stylesheet" type="text/css" />
<script src="/spread/assets/jquery.js" type="text/javascript"></script>
</head>

<body>

<div id="sawcebody">

<?php foreach($songs as $song) { ?>
	
<div class="song">
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
		<span class="name"><?=$song->_song_name?> (<?=$song->_album_name?>)</span>
		<span class="details"><?=$song->_display_name?> <span>is <?=$song->_artist_status?></span></span>
	</div>
	<div class="controls">
		<a href="javascript:parent.player.$fl('sw_player').setTrack(<?=$song->_artist?>,<?=$song->_id?>);"><img src="/spread/assets/btn_play.gif"></a>
		<a href="javascript:parent.player.$fl('sw_player').stopTrack();"><img src="/spread/assets/btn_stop.gif"></a>
		<a href="<?=build_link('music','buy',$song->_id,'via',$ref)?>" target="_blank" onclick="return parent.sawce.sw_buy(<?=$song->_id?>);" id="buy_<?=$song->_id?>" ><img src="/spread/assets/btn_buy.gif"></a>
	</div>
	<div class="clear"></div>
</div>

<?php } ?>

</div>

</body>
</html>