<?php
	
	global $matrix;
	global $points;
	global $max;
	
	$max = array('north' => FALSE, 'south' => FALSE, 'east' => FALSE, 'west' => FALSE);
	
	$rate = 0.001;
	
	$matrix = Extractor::generate_tag_matrix();

	$points = array();
	for ($i = 0; $i < sizeof($matrix); $i++) {
		$points[] = array(rand(0,1000)/1000, rand(0,1000)/1000);
	}
	
	$last_error = 0;	
	while(true) {
		$total_error = 0;

		$gradient = array();
		for ($x = 0; $x < sizeof($matrix); $x++) {
			$gradient[] = array(0,0);
			for ($y = 0; $y < sizeof($matrix); $y++) {
				if ($x != $y) {
					$distance = sqrt(pow($points[$x][0]-$points[$y][0],2)+pow($points[$x][1]-$points[$y][1],2));
					
					$matrix_distance = ((1 - $matrix[$x][$y])+0.1);
					$error = ($distance - $matrix_distance)/$matrix_distance;
					
					$gradient[$x][0] += (($points[$x][0] - $points[$y][0])/$distance)*$error;
					$gradient[$x][1] += (($points[$x][1] - $points[$y][1])/$distance)*$error;

					$total_error += abs($error);
				}
			}
		}
		
		if ($last_error && ($last_error < $total_error)) {
			break;
		}
		
		$last_error = $total_error;

		for ($i = 0; $i < sizeof($matrix); $i++) {
			$points[$i][0] -= $rate*$gradient[$i][0];
			$points[$i][1] -= $rate*$gradient[$i][1];
		}
	}
	
	for ($i = 0; $i < sizeof($matrix); $i++) {
		if (($max['west'] === FALSE) || ($points[$i][0] < $max['west'])) $max['west'] = $points[$i][0];
		if (($max['east'] === FALSE) || ($points[$i][0] > $max['east'])) $max['east'] = $points[$i][0];
		if (($max['south'] === FALSE) || ($points[$i][1] > $max['south'])) $max['south'] = $points[$i][1];
		if (($max['north'] === FALSE) || ($points[$i][1] < $max['north'])) $max['north'] = $points[$i][1];
	}	

?>