<sawce>
<?php
	global $songs;
	
	if ($songs) {
		foreach ($songs as $song) {
			echo Util::render_song($song, 'xml');
		}
	}	
?>
</sawce>