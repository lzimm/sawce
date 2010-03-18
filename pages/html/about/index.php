<?php define_header_part(1, 'Sawce'); ?> 
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="about_panel" class="page shdw_bot"><div class="padding">                            
<h3>About Sawce</h3>

<table cellspacing="0" cellpadding="0" border="0" class="padless"><tr><td width="240" valign="top" class="first">
	<ul class="peripheral">
		<li class="first">Founded: 2007</li>
		<li>Founder: Lewis Zimmerman</li>
		<li>Location: Vancouver, BC</li>
	</ul>
</td><td valign="top">

<p>Sawce inspires music lovers to discover, support and promote good music, and to empower artists to understand and influence the path their music takes as it spreads through fans.</p>

<p>Sawce is a new kind of marketplace: a new way to look at music. We believe that though the sweat and tears an artist pours into a song is something to value, its true worth doesn't come out until that song becomes a part of something great--until that song builds unions between people.</p>

<p>Sawce is about loving music. It's about the people who love music enough to share it: the mavens and connectors who spread it through society so that it can touch people on new and profound ways. It's about building a marketplace around the people who love music, rather than just the music itself.  And it's about rediscovering the incentives that make music worth what it's worth in the first place.</p>

<p>Sawce is about building ways for Artists and Fans to connect in bold new ways. It's about allowing the Internet to empower Artists, rather than threaten them. It's about letting fans become part of the enterprise.</p>

</tr></tr></table>

</div></div>

<?=insert_sawce_footer();?> 

<?php end_focus_content(); ?>
<?/***********************************************************************    
* begin output buffer so we can grab the peripheral content
***********************************************************************/?>
<?php start_peripheral_content(); ?>

<div id="right_page"><div class="padding">
    <div class="right_top">Info</div>
    <div class="header">Learn More</div>
    <div class="section">
        <ul>
            <li class="first"><a href="<?=build_link('about')?>">About Sawce</a></li> 
            <li><a href="<?=build_link('about','spread')?>">How it Works</a></li>
            <li><a href="<?=build_link('about','contact')?>">Contact</a></li>
            <li><a href="<?=build_link('about','terms')?>">Terms and Conditions</a></li>
            <li><a href="<?=build_link('about','privacy')?>">Privacy Policy</a></li>
        </ul>
    </div>
</div></div>

<?php end_peripheral_content(); ?>