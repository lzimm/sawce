<?php if ($GLOBALS['components']['paginate']['next'] || $GLOBALS['components']['paginate']['prev']) { ?>

<div class="paginate">

<?php if ($GLOBALS['components']['paginate']['next']) { ?>
		
	<a href="<?=$GLOBALS['components']['paginate']['nextlink']?>" class="next_link"><span>Next</span></a>
		
<?php } ?>

<?php if ($GLOBALS['components']['paginate']['prev']) { ?>

	<a href="<?=$GLOBALS['components']['paginate']['prevlink']?>" class="prev_link"><span>Prev</span></a>

<?php } ?>

</div>

<?php } ?>