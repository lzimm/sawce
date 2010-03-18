<?php

	global $cache_file;
	
	global $matrix;
	global $points;
	global $max;
	
	$xscale = 800;
	$yscale = 800;
	
	$top = ($max['north'])*$yscale;
	$left = ($max['west'])*$xscale;

	$grid_width = ($max['east'] - $max['west'])*$xscale;
	$grid_height = ($max['south'] - $max['north'])*$yscale;

	if (file_exists($cache_file)) {
		unlink($cache_file);
	}
	
	$handle = fopen($cache_file, 'w');

	fwrite($handle, sprintf('<div class="graph" style="width: %spx; height: %spx">', $grid_width, $grid_height));

	foreach($matrix as $i => $point) {
		fwrite($handle, sprintf('<a href="%s" class="point" style="top: %spx; left: %spx;" onclick="return map_click(this);">
					<span>%s</span></a>', build_link($GLOBALS['cloud_type'],'tag',$matrix[$i][sizeof($matrix)]),
					$points[$i][1]*$yscale - $top, $points[$i][0]*$xscale - $left, $matrix[$i][sizeof($matrix)]));	
	}
	
	fwrite($handle, '</div>');
	
	fclose($handle);
	
?>
