<?php
	
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
<link href="/css/ingredients.css" rel="stylesheet" type="text/css" />
<link href="/css/sawce.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/jquery.curvycorners.source.js" type="text/javascript"></script>
<script src="/js/sawce.js?<?=time()?>" type="text/javascript"></script>
<script src="/js/flash_control.js" type="text/javascript"></script>
<script src="/js/swfupload.js" type="text/javascript"></script>
<script src="/js/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body class="my"><div id="pagewrapping"><div id="body">

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

<div id="header">	
    <div id="nav"><div id="nav_container">
        <div id="nav_links">
            <a href="<?=build_link('base')?>" id="lnk_home"><img src="/img/menu_home.gif" /></a>
            <a href="<?=build_link('music')?>" id="lnk_music"><img src="/img/menu_music.gif" /></a>
            <a href="<?=build_link('people')?>" id="lnk_people"><img src="/img/menu_people.gif" /></a>
            <a href="<?=build_link('my')?>" id="lnk_my"><img src="/img/menu_my.gif" /></a>
        </div> 
        <a href="<?=build_link('base')?>" id="logo"><img src="/img/sawce_fff.gif" /></a>
    </div></div>
    
    <div id="sub_nav">
        <div id="auth_menu" class="aux_menu"><div class="arrow">
            <div id="logout" class="<?=$logout_class?>">
                <a id="checkout_btn" class="checkout<?=$checkout_class?>" href="<?=build_link('music','pack','cart')?>">Cart</a>                                                                                 
                <a href="<?=build_link('my','messages')?>">Messages<?=Util::check_authed() && Util::as_authed()->get_new_messages() ? sprintf(" (%s)", sizeof(Util::as_authed()->get_new_messages())) : '';?></a>
                <a href="<?=build_link('users','logout','pg:'.$page.'/'.$section.'/'.$id)?>">Logout</a>
            </div>
            
            <div id="login" class="<?=$login_class?>"><a href="<?=build_link('users','pg:'.$page.'/'.$section.'/'.$id)?>">Login/Register</a></div>
        </div></div>
        
        <h1>Sorry / <span>Something Broke</span></h1>
        
        <div id="lnk_home_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('about')?>">About</a></div></div>
        <div id="lnk_music_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('music')?>">Explore</a><a href="<?=build_link('music','artists')?>">Artists</a></div></div>
        <div id="lnk_people_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('people')?>">Explore</a></div></div>
        <div id="lnk_my_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('my')?>">Dashboard</a>
<?php if (Util::check_authed() == UTYPE_ARTIST) { ?>
                <a href="<?=build_link('my','albums')?>">Albums</a><a href="<?=build_link('my','fans')?>">Fans</a>
<?php } ?>
                <a href="<?=build_link('my','sawce')?>">Sawce</a><a href="<?=build_link('my','library')?>">Library</a></div></div>
    </div>
</div>

<div id="content">
<div id="darkbox">
<div id="page_msgs"><?=Util::process_messages();?></div>

<div id="panel"><h3>Sorry, Something Broke. We'll work on fixing it right away!</h3></div>

</div>
</div>

<div id="footer"><div>
	&copy; 2008 Sawce Media Inc.
</div></div>
 
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