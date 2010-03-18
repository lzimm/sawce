<?php

	global $cache_file;
	
	global $matrix;
	global $points;
	global $max;
	
	$xscale = 1000;
	$yscale = 1000;
	
	$top = ($max['north'])*$yscale;
	$left = ($max['west'])*$xscale;

	$grid_width = ($max['east'] - $max['west'])*$xscale;
	$grid_height = ($max['south'] - $max['north'])*$yscale;

	if (file_exists($cache_file)) {
		unlink($cache_file);
	}
	
	$handle = fopen($cache_file, 'w');

	fwrite($handle, '<tags>');

	foreach($matrix as $i => $point) {
		fwrite($handle, sprintf("<tag name='%s' y='%s' x='%s' />", 
						$matrix[$i][sizeof($matrix)], 
						$points[$i][1]*$yscale - $top, 
						$points[$i][0]*$xscale - $left));	
	}
	
	fwrite($handle, '</tags>');
	
	fclose($handle);
	
?>