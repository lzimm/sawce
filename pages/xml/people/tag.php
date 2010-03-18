<?php
    global $id;
    
    global $related_tags;
    global $related_spreaders;
?>
<sw_tag>
    <tag_name><![CDATA[<?=$id?>]]></tag_name>

    <related_tags>
<?php foreach($related_tags as $tag) { ?>
        <tag_name><![CDATA[<?=$tag['genre']?>]]></tag_name>
<?php } ?>    
    </related_tags>

    <related_spreaders>
<?php foreach($related_spreaders as $spreader) { ?>
        <spreader>
            <maven><![CDATA[<?=$spreader['maven']?>]]></maven>
            <connector><![CDATA[<?=$spreader['connector']?>]]></connector>
            <count><![CDATA[<?=$spreader['count']?>]]></count>
        </spreader>
<?php } ?>    
    </related_spreaders>
</sw_tag>