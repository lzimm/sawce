<?php
    global $id;
    
    global $related_tags;
    global $related_artists;
    global $related_songs;
?>
<sw_tag>
    <tag_name><![CDATA[<?=$id?>]]></tag_name>

    <related_tags>
<?php foreach($related_tags as $tag) { ?>
        <tag_name><![CDATA[<?=$tag['genre']?>]]></tag_name>
<?php } ?>    
    </related_tags>

    <related_artists>
<?php foreach($related_artists as $artst) { ?>
        <artist>
            <username><![CDATA[<?=$artist->_username?>]]></username>
            <display_name><![CDATA[<?=$artist->_display_name?>]]></display_name>
        </artist>
<?php } ?>    
    </related_artists>

    <related_songs>
<?php foreach($related_songs as $song) { ?>
        <song>
            <id><![CDATA[<?=$song->_id?>]]></id>
            <song_name><![CDATA[<?=$song->_song_name?>]]></song_name>
            <artist><![CDATA[<?=$song->_artist?>]]></artist>
            <artist_user><![CDATA[<?=$song->_artist_user?>]]></artist_user>
            <display_name><![CDATA[<?=$song->_display_name?>]]></display_name>
        </song>
<?php } ?>    
    </related_songs>

</sw_tag>