<?php
	global $ref;
	global $songs;
	global $req;
    global $spread_link;
?>

var sawce = {
	songs: [
<?php foreach ($songs as $i => $song) { ?>			
			{

<?=Util::render_song($song, 'json')?>
,play: function(callback) { sawce.player.play(<?=$i?>, callback); }
			}<?=($i < (sizeof($songs) - 1)?',':'')?>			
<?php } ?>
		],
	player: {
		stop_callback: null,
		
		set_stop_callback: function(func) { sawce.player.stop_callback = func; },
		
		play: function(id, callback) {
			sawce.player.get().setTrack(sawce.songs[id].artist, sawce.songs[id].id);
			sawce.current_song = id;
			callback(id);
		},
		
		stop: function(id, callback) {
			sawce.player.get().stopTrack();
			sawce.current_song = null;
			
			if (callback) {
				callback(id);
			} else if (sawce.player.stop_callback) {
				sawce.player.stop_callback(id);
			}
		},
		
		get: function() {
			var transports = [
				function() { return window.document['sawce_player']; },
				function() { return document.embeds['sawce_player']; },
				function() { return document.getElementById['sawce_player']; }
			];
	
			var obj;
	
			for(var i = 0; i < transports.length; i++) {
				try {	
					return transports[i]();
				} catch (ex) {
				}
			}

			return false;
		}
	},
	current_song: null
};