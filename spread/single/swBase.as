package {

	import flash.display.Sprite;
	
	import flash.media.Sound;
    import flash.media.SoundChannel;
    import flash.media.SoundMixer;
    import flash.media.SoundTransform;
	import flash.media.SoundLoaderContext;
	
    import flash.utils.*;
	import flash.events.*;
    import flash.errors.*;

	import flash.net.navigateToURL;
	import flash.net.URLLoader;
	import flash.net.URLRequest;

	public class swBase extends Sprite {
        private var _sound: Sound;
        private var _sc: SoundChannel;
        private var _isPlaying: int;
        private var _playtime: int;
		private var _context:SoundLoaderContext;

		private var _urlLoader: URLLoader;
		
		private var _sawceUrl: String;
		
		private var _prevPos: Number;
		private var _songID: Number;
		
		public function swBase(sawceUrl: String = "http://sawcesongs.s3.amazonaws.com/") {
            _sc = new SoundChannel();
			_playtime = 0;
			_isPlaying = 0;
			
			var voltransform: SoundTransform = _sc.soundTransform;
            voltransform.volume = 0.6;
            _sc.soundTransform = voltransform;
			
			_context = new SoundLoaderContext(1000, true);
			
			_sawceUrl = sawceUrl;
		}
		
		private function getInfo(e:Event) {			
			if (_isPlaying) {
				if ((_sound.bytesTotal > 1024) && (Math.abs(_sound.bytesLoaded - _sound.bytesTotal) < 0.005) && (Math.abs(_sc.position - _prevPos) < 0.005)) {
					stopScript(e);
				}
				
				_prevPos = _sc.position;
			}
		}
		
		private function stopScript(e:Event) {
			trace("SOUNDCOMPLETE");
			var url:URLRequest = new URLRequest("javascript:stop_track("+_songID+"); void(0);"); 
			navigateToURL(url, "_self");
		}

		private function ioErrorHandler(event: IOErrorEvent): void {
        
		}

		public function onPlay(artist, song) {
			_sc.stop();
			
			var url: String = _sawceUrl + artist + "/" + song + ".mp3";
			
			_songID = song;
			_sound = new Sound();
			_sound.load(new URLRequest(url), _context);
			_sound.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
			_sc = _sound.play(_playtime);
			_isPlaying = 1;
			
			swGlobal.vars.playing = 1;
			
			_sc.addEventListener(Event.SOUND_COMPLETE, stopScript);
        }
		
		public function onPause() {
			if (_isPlaying) {
				_playtime = _sc.position;
				_sc.stop();
				_isPlaying = 0;
				
				swGlobal.vars.playing = 0;
			}
		}
		
		public function onStop() {
			if (_isPlaying) {
				_playtime = 0;
				_sc.stop();
				_isPlaying = 0;
				
				swGlobal.vars.playing = 0;
			}
		}
		
	}
	
}