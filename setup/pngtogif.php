<?php

	$image_prefix = array('rbtn_', 'rc_tbf_');
	$conversions = array(array(), array());
	
	for ($i = 0; $i < 16; $i++) {
		$n = dechex($i);
		
		for ($x = 0; $x < sizeof($image_prefix); $x++) {
			$name = '../_img/' . $image_prefix[$x] . $n . $n . $n . '.png';
			if (file_exists($name)) {
				
				echo "<div style='border: 4px solid #000; padding: 20px;'><h1>" . $name . "</h1>";
				
				$in = imagecreatefrompng($name);
				imagesavealpha($in, true);
				
				list($width, $height) = getimagesize($name);
				
				for ($ii = 0; $ii < 16; $ii++) {
					$nn = dechex($ii);
				
					$rgb = hexdec($nn . $nn);
				
					$out = imagecreatetruecolor($width, $height);
		    		$trans_colour = imagecolorallocate($out, $rgb, $rgb, $rgb);
		    		imagefill($out, 0, 0, $trans_colour);
		    		imagecolortransparent($out, $trans_colour);
					//imagecopymerge($out, $in, 0, 0, 0 ,0, $width, $height, 100);
					imagecopy($out, $in, 0, 0, 0 ,0, $width, $height);
					imageInterlace($out);
								
					$outname = '../_img/' . $image_prefix[$x] . $n . $n . $n . '_' . $nn . $nn . $nn . '.gif';

					imagegif($out, $outname);
				
					printf("<img src='%s' /> to <img src='%s' />", $name, $outname);
				}
				
				echo "</div>";
					
			}
		}
	}

	for ($i = 0; $i < 16; $i++) {
		$n = dechex($i);
		
		printf("* html div.focus div.rc_%s div.rc_t div, * html div.focus div.rc_%s div.rc_b div { background-image: url(/_img/rc_tbf_%s_333.gif); }\n", ($n.$n.$n),($n.$n.$n),($n.$n.$n));
		printf("* html div.transitional_menu div.rc_%s div.rc_t div, * html div.transitional_menu div.rc_%s div.rc_b div { background-image: url(/_img/rc_tbf_%s_444.gif); }\n", ($n.$n.$n),($n.$n.$n),($n.$n.$n));
		printf("* html div.peripheral div.rc_%s div.rc_t div, * html div.peripheral div.rc_%s div.rc_b div { background-image: url(/_img/rc_tbf_%s_666.gif); }\n", ($n.$n.$n),($n.$n.$n),($n.$n.$n));

		printf("* html div.focus div.rc_%s div.rc_t div div div, * html div.focus div.rc_%s div.rc_b div div div { background: none; }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.transitional_menu div.rc_%s div.rc_t div div div, * html div.transitional_menu div.rc_%s div.rc_b div div div { background: none; }\n", ($n.$n.$n),($n.$n.$n),($n.$n.$n));
		printf("* html div.peripheral div.rc_%s div.rc_t div div div, * html div.peripheral div.rc_%s div.rc_b div div div { background: none; }\n", ($n.$n.$n),($n.$n.$n),($n.$n.$n));

		printf("* html div.fill a.rf_%s { background-image: url(/_img/rbtn_%s_ccc.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.fill a.rf_%s span { background-image: url(/_img/rbtn_%s_ccc.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.fill a.rt_%s:hover { background-image: url(/_img/rbtn_%s_ccc.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.fill a.rt_%s:hover span { background-image: url(/_img/rbtn_%s_ccc.gif); }\n", ($n.$n.$n),($n.$n.$n));
		
		printf("* html div.focus a.rf_%s { background-image: url(/_img/rbtn_%s_333.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.focus a.rf_%s span { background-image: url(/_img/rbtn_%s_333.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.focus a.rt_%s:hover { background-image: url(/_img/rbtn_%s_333.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.focus a.rt_%s:hover span { background-image: url(/_img/rbtn_%s_333.gif); }\n", ($n.$n.$n),($n.$n.$n));

		printf("* html div.transitional_menu a.rf_%s { background-image: url(/_img/rbtn_%s_444.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.transitional_menu a.rf_%s span { background-image: url(/_img/rbtn_%s_444.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.transitional_menu a.rt_%s:hover { background-image: url(/_img/rbtn_%s_444.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.transitional_menu a.rt_%s:hover span { background-image: url(/_img/rbtn_%s_444.gif); }\n", ($n.$n.$n),($n.$n.$n));
		
		printf("* html div.peripheral a.rf_%s { background-image: url(/_img/rbtn_%s_666.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.peripheral a.rf_%s span { background-image: url(/_img/rbtn_%s_666.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.peripheral a.rt_%s:hover { background-image: url(/_img/rbtn_%s_666.gif); }\n", ($n.$n.$n),($n.$n.$n));
		printf("* html div.peripheral a.rt_%s:hover span { background-image: url(/_img/rbtn_%s_666.gif); }\n", ($n.$n.$n),($n.$n.$n));

		printf("* html div div.rc_%s button div { background-image: url(/_img/rbtn_ccc_%s.gif); }\n", ($n.$n.$n), ($n.$n.$n));
		printf("* html div div.rc_%s button div span { background-image: url(/_img/rbtn_ccc_%s.gif); }\n", ($n.$n.$n), ($n.$n.$n));

		
		for ($ii = 0; $ii < 16; $ii++) {
			$nn = dechex($ii);
		
			printf("* html div div.rc_%s a.rf_%s { background-image: url(/_img/rbtn_%s_%s.gif); }\n", ($n.$n.$n), ($nn.$nn.$nn), ($nn.$nn.$nn), ($n.$n.$n));
			printf("* html div div.rc_%s a.rf_%s span { background-image: url(/_img/rbtn_%s_%s.gif); }\n", ($n.$n.$n), ($nn.$nn.$nn), ($nn.$nn.$nn), ($n.$n.$n));
			printf("* html div div.rc_%s a.rt_%s:hover { background-image: url(/_img/rbtn_%s_%s.gif); }\n", ($n.$n.$n), ($nn.$nn.$nn), ($nn.$nn.$nn), ($n.$n.$n));
			printf("* html div div.rc_%s a.rt_%s:hover span { background-image: url(/_img/rbtn_%s_%s.gif); }\n", ($n.$n.$n), ($nn.$nn.$nn), ($nn.$nn.$nn), ($n.$n.$n));
		}
	}

?>
