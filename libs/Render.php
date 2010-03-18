<?php

class Render {

	public static function song($song, $my = FALSE) {        
        $html = sprintf('<div class="song_playable"><a href="#" id="songbtn_%s" rel="%s" class="btn play_btn"><span class="btn">Play</span></a>
                    <a href="%s" id="buybtn_%s" rel="%s" class="btn buy_btn lbox"><span class="btn">%s</span></a> 
                    <a href="%s" class="name">%s</a></div>', $song->_id, $song->_artist, 
                    $my ? build_download_link($song->_artist, $song->_id, $song->_secret) : build_link('music','cart',$song->_id,'pg:'.$GLOBALS['iref']), 
                    $song->_id, $song->_artist, $my ? 'Download' : 'Buy', 
                    $my ? build_link('my','song',$song->_id) : build_link('music','song',$song->_id), $song->_song_name);
                    
		return $html;
	}
    
    public static function song_item($song, $prebuttons = array(), $left = true, $name = true) {
        $buttons = '';
        foreach($prebuttons as $button) {
            $buttons .= sprintf('<a href="%s" class="btn"%s><span class="btn">%s</span></a>', 
                            $button[0], $left ? ' style="float: left; margin-left: 0; margin-right: 5px;"' : '', $button[1]);
        }
        
        $html = sprintf('<div class="song_playable"><a href="#" id="songbtn_%s" rel="%s" class="btn play_btn"><span class="btn">Play</span></a>
                    %s<a href="%s" class="name">%s</a></div>', $song->_id, $song->_artist, $buttons, 
                    $my ? build_link('my','song',$song->_id) : build_link('music','song',$song->_id), $song->_song_name);
                    
        return $html;
    }
    
    public static function song_options($song, $my = FALSE) {        
        $html = sprintf('<div class="song_options"><a href="#" id="songopt_%s" rel="%s" class="play_opt"><span>Play</span></a>
                    <a href="%s" id="buyopt_%s" rel="%s" class="buy_opt lbox"><span>%s</span></a></div>', 
                    $song->_id, $song->_artist, 
                    $my ? build_download_link($song->_artist, $song->_id, $song->_secret) : build_link('music','cart',$song->_id,'pg:'.$GLOBALS['iref']),
                    $song->_id, $song->_artist,
                    $my ? 'Download' : 'Buy');
                    
        return $html;
    }
	
}

?>