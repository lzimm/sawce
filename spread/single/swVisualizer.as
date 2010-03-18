﻿package {
	
    import flash.display.Shape;
    import flash.display.Sprite;
    import flash.media.Sound;
    import flash.media.SoundChannel;
    import flash.media.SoundMixer;
    import flash.media.SoundTransform;
    import flash.utils.*;
    import flash.events.*;
    import flash.geom.*;
    import flash.filters.*;
    import flash.errors.IOError;

    public class swVisualizer extends Sprite {
        private var _barArray: Array;
        private var _lineArray: Array;
        private var _numBars: int;
		private var _ba: ByteArray;

        public function swVisualizer() {
			_barArray = new Array();
            _lineArray = new Array();
            _ba = new ByteArray();
            addEventListener(Event.ENTER_FRAME, animateBars);
			
			_numBars = 15;
        }

        public function createSpectrumBars(sWidth, sHeight): void {			
			var _wScale: Number = sWidth/_numBars;
			
            for (var i: int = 0; i < _numBars; i++) {
                var bar: Shape = new Shape();
                bar.graphics.beginFill(0x000000);
                bar.graphics.drawRect(0, 0, _wScale, sHeight);
                bar.graphics.endFill();
                bar.y = 0;
                bar.x = i*_wScale;
                addChild(bar);
                _barArray.push(bar);

                var line1: Shape = new Shape();
                line1.graphics.beginFill(0x000000);
                line1.graphics.drawRect(0, 0, _wScale, sHeight/2);
                line1.graphics.endFill();
                line1.y = 0;
                line1.x = i*_wScale;
                addChild(line1);
                _lineArray.push(line1);
            }
        }
		
        function animateBars(event: Event): void {
            try {
				SoundMixer.computeSpectrum(_ba, true, 0);
				for (var i: int = 0; i < _numBars; i++) {
					_barArray[i].alpha = 0.05 + Math.abs(_ba.readFloat());
					_lineArray[i].alpha = 0.05 + Math.abs(_ba.readFloat());
				}
			} catch (e) {
				for (var n: int = 0; n < _numBars; n++) {
					if (swGlobal.vars.playing) {
						_barArray[n].alpha = Math.random() * 0.75;
						_lineArray[n].alpha = Math.random() * 0.75;
					} else {
						_barArray[n].alpha = 0.05;
						_lineArray[n].alpha = 0.05;
					}
				}
			}
        }
    }
}