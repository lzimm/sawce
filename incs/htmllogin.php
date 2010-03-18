<?php	
	global $u_email;
	global $u_pass;
	global $u_submit;

	global $r_name;
	global $r_pass;
	global $r_pass_chk;
	global $r_email;
	global $r_display;
	global $r_profile;
	global $r_type;
	global $r_check;
	global $r_submit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Spread Music. Spread Sawce.</title>
<link href="/css/my.css" rel="stylesheet" type="text/css" />
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
            <div id="login"><a href="<?=build_link('users','pg:'.$page.'/'.$section.'/'.$id)?>">Login/Register</a></div>
        </div></div>
        <h1>Spread Sawce / <span>Login</span></h1>
        
        <div id="lnk_home_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('about')?>">About</a></div></div>
        <div id="lnk_music_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('music')?>">Explore</a><a href="<?=build_link('music','artists')?>">Artists</a></div></div>
        <div id="lnk_people_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('people')?>">Explore</a></div></div>
        <div id="lnk_my_sub" class="page_menu"><div class="arrow"><a href="<?=build_link('my')?>">Dashboard</a><a href="<?=build_link('my','sawce')?>">Sawce</a><a href="<?=build_link('my','library')?>">Library</a></div></div>
    </div>
</div>

<div id="content">
<div id="darkbox">

<div id="page_msgs"><?=Util::process_messages();?></div>

<div id="right_page"><div class="padding">
	<div class="right_top">Help</div>
    <div class="header">Tools</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('users','password')?>">Lost Password</a></li>
            <li><a href="<?=build_link('about','spread')?>">Learn More</a></li>
        </ul>
    </div>
</div></div>

<div id="login_panel" class="page shdw_bot">
<table cellspacing="0" cellpadding="0" border="0"><tr><td width="50%" valign="top" class="first">
<h3>Login</h3>
<form action="<?=build_link_secure($page,$section,$id,$action,$key,$reqstring)?>" method="post" class="makeform">
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td><label>Email</label><?=$u_email->build();?></td></tr>
        <tr><td><label>Password</label><?=$u_pass->build();?></td></tr>
        <tr><td><label>Login</label><?=$u_submit->build();?></td></tr>
    </table>
</form>
</td><td width="50%" valign="top">
<h3>Register</h3>
<form action="<?=build_link_secure($page,$section,$id,$action,$key,$reqstring)?>" method="post" class="makeform">
    <table class="makebasic" cellpadding="0" cellspacing="0">
        <tr><td><label>Email</label><?=$r_email->build();?></td></tr>
        <tr><td><label>Username</label><?=$r_name->build();?></td></tr>
        <tr><td><label>Display Name</label><?=$r_display->build();?></td></tr>
        <tr><td><label>Password</label><?=$r_pass->build();?></td></tr>
        <tr><td><label>Verify Password</label><?=$r_pass_chk->build();?></td></tr>
        <tr><td><label>Type</label><?=$r_type->build();?></td></tr>
        <tr><td><?=$r_check->build();?><?=HTML_LABEL_AGREE_TERMS?></tr></td>
        <tr><td><label>Register</label><?=$r_submit->build();?></td></tr>
    </table>
</form>
</td></tr></table>
</div>

<?=insert_sawce_footer();?>

</div>
<div id="lightbox">
    <div id="lightbox_position"><div id="lightbox_bg"></div>
    <div id="lightbox_fg"><div id="lightbox_content">
    </div></div></div>
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