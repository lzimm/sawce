<?php
	$tags = Extractor::get_tags(5);
	$albums = Extractor::get_hot_albums(5);

	$explore		= new TextValidator('tag', array('max_len' => 256, 'required' => true));
	$explore_btn	= new SubmitValidator('explore', array('label' => 'Explore'));
?>

<?php define_header('Spread Music / <span>Spread Sawce</span>'); ?>
<?/***********************************************************************	
* begin output buffer so we can grab the focus content
***********************************************************************/?>
<?php start_focus_content(); ?>

<img src="/img/front_test.jpg" />
                    
<div class="albums">
	<h3>New Albums</h3>
    <div class="album"><img src="img/album_thumb_1.png" /><div class="desc"><strong>Bleeding Love</strong><span>Leona Lewis</span></div></div>
    <div class="album"><img src="img/album_thumb_2.png" /><div class="desc"><strong>Bleeding Love</strong><span>Leona Lewis</span></div></div>
    <div class="album"><img src="img/album_thumb_3.png" /><div class="desc"><strong>Bleeding Love</strong><span>Leona Lewis</span></div></div>
    <div class="album last_album"><img src="img/album_thumb_4.png" /><div class="desc"><strong>Bleeding Love</strong><span>Leona Lewis</span></div></div>
    <div class="clear"></div>
</div>

<?php end_focus_content(); ?>