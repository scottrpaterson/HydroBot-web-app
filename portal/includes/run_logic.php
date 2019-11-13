<?php

include_once('/var/www/html/db.php');

// get existing values from db
$sql = "SELECT * FROM logic";
$logic_results = $conn->query($sql);

foreach ($logic_results as $key => $value) {
	
	// get values
	$rf_id 		= $value['rf_id'];
	$command 	= $value['command'];
	$hours 		= $value['hours'];
	$minutes 	= $value['minutes'];
	
	// get current time
	echo $hours = date('G');
	echo "-";
	echo $minutes = intval(date('i'));
	echo "|";

}