<?php
	global $id;
	global $action;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sawce > Spread Music</title>
<link rel="shortcut icon" href="/favicon.ico" />
</head>
<frameset rows="6,*,30" border="0">
	<frame src="/spread/assets/player.php" framespacing="0" frameborder="0" scrolling="no" noresize name="player">
	<frame src="/spread/frame/<?=$id?>/<?=$action?>" framespacing="0" frameborder="0" noresize name="frame">
	<frame src="/spread/assets/sawce.php?spread=<?=$id?>&action=<?=$action?>" framespacing="0" frameborder="0" scrolling="no" noresize name="sawce">
</frameset>
</html>