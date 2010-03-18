<?php
	
	include('../libs/S3.php');
	
	$s3 = new S3();
	$s3->createBucket('sawcesongs');
	$s3->createBucket('sawceart');

?>
