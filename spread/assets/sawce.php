<?php
	include('../../incs/required_light.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sawce > Spread Music</title>
<link rel="shortcut icon" href="/favicon.ico" />
<script src="/spread/assets/flash_control.js" type="text/javascript"></script>
<style>
* { margin: 0; padding: 0 }
body, html { height: 100%; }
body { background-color: #333; font-family: "Lucida Grande","Lucida Sans Unicode","Lucida Sans",Verdana,Arial,sans-serif; font-size: 11px; line-height: 18px; color: #fff; }
* body, * html { overflow: auto; }

a img { border: none; }

#player { border-bottom: 1px solid #696979; display: block; position: fixed; height: 5px; top: 0; left: 0; width: 100%; background-color: #000; }

#sawcebar { border-top: 1px solid #666; }
#sawcebar div.sawce_cnt { position: relative; height: 29px; width: 100%; display: block; }
#sawcebar div.sawce_bg { position: absolute; top: 0; left: 0; height: 100%; width: 100%; }
#sawcebar div.sawce_bg { background: transparent url(/spread/assets/bar_bg.png) repeat-x; }
#sawcebar div.sawce_fg { position: relative; }
#sawcebar div.sawce_fg img { position: absolute; top: 0; }
#sawcebar div.sawce_fg #logo { left: 15px; }
#sawcebar div.sawce_fg #checkout { right: 15px; top: 5px; }
</style>
</head><body>
<div id="sawcebar">
	<div class="sawce_cnt">
		<div class="sawce_bg"></div>
		<div class="sawce_fg">
			<img src="/spread/assets/bar_logo.png" id="logo">
			<a href="<?=build_link('music','pack',($_GET['action']!='album')?$_GET['spread']:0)?>" target="_blank" id="sw_checkout" class="disabled" style="display: none;"><img src="/spread/assets/btn_checkout.gif" id="checkout"></a>
		</div>
	</div>
</div>
</body></html>