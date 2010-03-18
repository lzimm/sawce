<?php
	global $header;
    global $header_link;
    global $header_parts;
	
	global $focus;
	global $peripheral;
	global $footer;
    global $lightbox_preload;
	
	global $page;
	global $section;
	global $id;
    global $action;
    global $key;
    global $req;

	if (!$header) { 
        $header = '';

        $GLOBALS['h_page'] = ($GLOBALS['page'] == 'base') ? 'home' : $GLOBALS['page'];
        for ($i = 0; $i < sizeof($get_order); $i++) {
            if (($i == (sizeof($get_order) - 1)) 
                    || !(isset($GLOBALS[$get_order[$i+1]]) && $GLOBALS[$get_order[$i+1]] || (isset($header_parts[$i+1]) && $header_parts[$i+1]))
                    || ($GLOBALS[$get_order[$i+1]] == 'index') && !(isset($header_parts[$i+1]) && $header_parts[$i+1])) {
                        
                if ($header_parts && isset($header_parts[$i])) {
                    $GLOBALS['h_' . $get_order[$i]] = $header_parts[$i][0];
                } else {
                    $GLOBALS['h_' . $get_order[$i]] = $GLOBALS[$get_order[$i]];
                }
                
                $header .= sprintf("<span>%s</span>", ucfirst($GLOBALS['h_' . $get_order[$i]]));
                $i = sizeof($get_order);
            } else {
                if ($header_parts && isset($header_parts[$i])) {
                    $GLOBALS['h_' . $get_order[$i]] = $header_parts[$i][0];
                } else {
                    $GLOBALS['h_' . $get_order[$i]] = $GLOBALS[$get_order[$i]];
                }
                
                $header .= sprintf("%s / ", ucfirst($GLOBALS['h_' . $get_order[$i]]));
            }
        }
	}                
    
    global $lbmsg;
	
	$pageclass = ($page == 'music' || $page == 'people' || $page == 'my') ? $page : 'home';
    if ($page == 'users') $pageclass = 'my';
	
	$checkout_class = (Util::cart_get() && sizeof(Util::cart_get()))?' checkout_show':'';
	if (Util::check_authed()) {
		$logout_class = '';
		$login_class = 'hide';
	} else {
		$logout_class = 'hide';
		$login_class = '';		
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Spread Music. Spread Sawce.</title>
<link href="/css/<?=$pageclass?>.css" rel="stylesheet" type="text/css" />
<link href="/css/reset.css" rel="stylesheet" type="text/css" />
<link href="/css/sawce.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/jquery.curvycorners.source.js" type="text/javascript"></script>
<script src="/js/sawce.js?<?=time()?>" type="text/javascript"></script>
<script src="/js/flash_control.js" type="text/javascript"></script>
<script src="/js/swfupload.js" type="text/javascript"></script>
<script src="/js/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body class="<?=$pageclass?>">
	
	<div id="container">
	<div id="frame_shadow_2">
	<div id="frame_shadow_1">
	<div id="frame">
	<div id="header">
	    	<div id="auth">
	            <div class="auth_bar">
	                <a href="#" class="ab_cart"><span>Cart</span></a>
	                <a href="#" class="ab_msg"><span class="notice">Messages</span></a>
	                <a href="#" class="ab_logout"><span>Logout</span></a>
	            </div>
	        </div>
	    	<div id="logo"><a href="<?=build_link('base')?>"><img src="/img/logo.png" border="0" /><a></div>
	        <div class="clear"></div>
	    </div>

	    <div id="inset">
	    <div id="menubar">
	        <div id="navlinks">
	            <a href="<?=build_link('base')?>" id="lnk_home"><img src="/img/menu_home.png" /></a>
	            <a href="<?=build_link('music')?>" id="lnk_music"><img src="/img/menu_music.png" /></a>
	            <a href="<?=build_link('people')?>" id="lnk_people"><img src="/img/menu_people.png" /></a>
	            <a href="<?=build_link('my')?>" id="lnk_my"><img src="/img/menu_my.png" /></a>
	        </div>
	    	<form method="post" action=""><input type="text" value="Search Artists" /></form>
	  	</div>

	    <div id="box">
		<div id="mask">
	    	<div id="left">
	        	<div id="bodywrap">
	        		<div id="body">
	                <div id="heading"><div id="heading_pad"><div id="span"><span><?=$header?></span></div></div></div>
	                <div id="main">
					<?=$focus?>
	                </div></div>
	            </div>
	            <div id="side">
	            <?=$peripheral?>
				<div id="sidemenu">
					<?=render_page_menu($page, 'Menu');?>
				</div>
	            </div>
	        </div>
	    </div>
	    <div class="clear"></div>
	    </div>

	    <div id="footbox">
	    	&copy; 2008 Sawce Media Inc.
	    </div>
	</div>
	</div>	
    </div>
	</div>
	</div>

<div id="player_container"><div>
		<script type="text/javascript">
		AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','100%','height','100%','src','/spread/single/track','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','/spread/single/track','name','sw_player','id','sw_player','swliveconnect','true','wmode','transparent' ); //end AC code
		</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" table height="100%" id="sw_player">
			<param name="movie" value="/spread/single/track.swf" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent">
			<embed src="/spread/single/track.swf" table height="100%" name="sw_player" swliveconnect="true" quality="high" wmode="transparent" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
		</object></noscript>		
</div></div>

<script src="/js/footer.js" type="text/javascript"></script> 
<?php if (!isset($_SERVER['HTTPS'])) { ?>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
_uacct = "UA-2228857-1";
urchinTracker();
</script>
<?php } ?>

</body>
</html>
