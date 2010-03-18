<?php
	include("../config/config.php");
	include("../incs/required.php");
	
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
		
		error_log($total_error);
		
		if ($last_error && ($last_error < $total_error)) {
			break;
		}
		
		$last_error = $total_error;

		for ($i = 0; $i < sizeof($matrix); $i++) {
			$points[$i][0] -= $rate*$gradient[$i][0];
			$points[$i][1] -= $rate*$gradient[$i][1];
		}
	}

?>

<style>

	table { opacity: 0.25; filter: alpha(opacity=25); }

	th, td { width: 50px; height: 50px; background-color: #eee; border: 1px solid #ddd; }

</style>

<table cellspacing="1">
<tr>
	<th>*</th>

<?php foreach($matrix as $row) { ?>
	
	<th><?=$row[sizeof($row)-1]?></th>
	
<?php } ?>
</tr>

<?php foreach($matrix as $row) { ?>
	<tr>
		<th><?=$row[sizeof($row)-1]?></th>
	

<?php foreach($row as $i => $col) { if ($i < (sizeof($row) - 1)) { ?>

		<td align="center"><?=$col?></td>

<?php } } ?>

	</tr>
<?php } ?>

</table>

<?php foreach($matrix as $i => $point) { ?>
	
	<b style="background-color: #666; border: 1px solid #999; color: #fff; padding: 1px; position: absolute; top: <?=$points[$i][1]*250+100?>px; left: <?=$points[$i][0]*250+100?>;"><?=$matrix[$i][sizeof($matrix)]?></b>
	
<?php } ?>
