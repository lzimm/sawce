<?php
	
	include('include.php');
	include('header.php');
	
	if (!$user) {
		include('login.php');
	}
	
	if ($user) {
		$sawce 	= $user->sawce_get();
		$fbml 	= "<fb:subtitle>My Sawce<fb:action href='http://apps.facebook.com/sawce_app/'>Spread It</fb:action></fb:subtitle>";
		
		$fbml	.= '<div style="background: #ddd url(http://fb.sawce.net/img/top_bg.png) scroll 0 -30px repeat-x; display: block; padding: 0; border: 1px solid #ccc;">';
		$fbml	.= '<div style="background: transparent url(http://fb.sawce.net/img/sawce_logo.png) scroll 0 -30px; width: 67px; height: 35px; margin-left: 20px;"></div>';
		$fbml	.= '<div style="background: #fff url(http://fb.sawce.net/img/drop_fff.png) repeat-x; display: block; color: #333; margin: 0; padding: 20px 10px 0px 10px;">';
		
		$songs = '';
		foreach($sawce as $i => $song) {
			$songs	.= sprintf('<div style="padding: 5px; %s">
									<div style="width: 40px; height: 40px; float: left; display: block; overflow: hidden;">
										<fb:mp3 src="%s" width="35" height="40" /></div>
									<div style="margin-left: 40px; padding: 3px;">
										<a href="%s" target="_blank">%s</a> by <a href="%s" target="_blank">%s</a></div>
									<div style="margin-left: 40px; padding: 3px; display: block; border-top: 1px solid #ccc;">
										<a href="%s" target="_blank">Buy It</a></div>
								</div>',
							$i?"border-top: 1px solid #ccc;":"",
							AMAZON_SONG_BASE . $song->_artist . '/' . $song->_id . '.mp3',
							HTTP_LINK_BASE . 'music/song/' . $song->_id, $song->_song_name, 
							HTTP_LINK_BASE . 'music/artist/' . $song->_artist, $song->_display_name, 
							HTTP_LINK_BASE . 'music/buy/' . $song->_id);
		}
		$fbml .= $songs;
		
		$fbml .= '</div></div>';
		$profile = '<fb:fbml version="1.1"><fb:wide>'.$fbml.'<fb:wide></fb:wide><fb:narrow>'.$fbml.'</fb:narrow></fb:fbml>';
		
		$facebook->api_client->profile_setFBML($profile, $fb_user);
		
		echo '<div class="padding">';
		echo '<h1>Your Sawce</h1>';
		echo '</div>';
		echo '<div id="embed">';
		echo '<fb:iframe frameborder="0" src="http://sigma.sawce.net/spread/sawce/'.$user->_username.'/bg:3B5998" style="width: 100%; height: 100%;"></iframe>';
		echo '</div>';
	}
	
	include('footer.php');
	
?>