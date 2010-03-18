<?php define_header_part(1, 'How It Works'); ?> 
<?/***********************************************************************    
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<div id="about_panel" class="page shdw_bot"><div class="padding">                            
<h3>How it Works</h3>

<table cellspacing="0" cellpadding="0" border="0" class="padless"><tr><td width="240" valign="top" class="first">
	<ul class="peripheral">
		<li class="first">We enable Artists to sell Music directly to their Fans</li>
		<li>Each time an Artist sells a song, they get to keep between <strong>90% and 95%</strong> of the purchase price</li>
		<li>Once Fans buy Music, we enable them to help Artists promote the songs they love</li>
		<li>Each time a Fan helps promote a song, they <strong>split the revenue</strong> with the Artist</li>
		<li>As Fans help the Music spread, we take note and help identify who the world's greatest fans are so Artists can <strong>identify people who should be their fans</strong> and put their music in front of them</li>
		<li>Sawce is about building an ecosystem around Music and its Fans</li>
		<li>Sawce is about putting Music in front of Fans conveniently</li>
		<li>Sawce is about Music finding its Fans</li>
	</ul>
</td><td valign="top">

<h4>It Starts With Artists</h4>
<p>Sawce is a music distribution platform that enables artists to take control of their own music distribution. After adding music to the Sawce Platform, Artists can drive sales through their websites/blogs/myspace/facebook profiles with simple widgets or complex integrations into the platform, and begin their path to the Spread by letting their fans purchase their music right where they'd most expect it.</p>

<br /><br />

<h4>Then It Moves Through Fans</h4>
<p>After fans purchase the songs they love, they can really start to get involved in the music by promoting them to all their friends. Sawce enables fans to add their favorite songs to their own little secret Sawce and use their websites/blogs/myspace/facebook profiles to help the music move. Each time a song gets sold through them, they split the reward with the artist, depending on whatever share the artist decides.</p>

<br /><br />

<h4>And It All Gets Mapped</h4>
<p>As music lovers buy music from their favorite artists, Sawce keeps track, and lets those artists contact their fans one on one. As music lovers help promote music by their favorite artists, Sawce keeps track, and tries to recognize who the most influential fans on the planet are. If you're a trend setter, a taste maker, or just one really popular individual, you're a very important part of the musical ecosystem, and its important to recognize that. So Sawce allows artists to determine who the most influential fans are, that can really make their music spread. Those fans may be music lovers who've enjoyed and promoted their music in the past, or they could be fans of another artist with a similar sound. Sawce maps out influential fans by song, artist and genre, and lets artists reach out to them any time they have something they think they'll love.</p>

</td></tr></table>

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