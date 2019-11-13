<?php

include_once('/var/www/html/db.php');




// check air temp values

$air 		= exec("python2 /var/www/html/portal/includes/get_temp.py 2>&1");
$air 		= explode('|', $air);
$air_temp	= $air[0];


// get temp units
$sql = "SELECT value FROM settings WHERE setting = 'units_degrees_format'";
$settings_results = $conn->query($sql);

list($units_degrees_format) = mysqli_fetch_row($settings_results);



// get air logic
$sql = "SELECT value FROM settings WHERE setting = 'air_temp_set_point'";
$air_set_points = $conn->query($sql);


foreach ($air_set_points as $key => $value) {
	
	$air_set = unserialize($value['value']);
	extract($air_set);
	
	
	// calculate high and low values
	$air_temp_high	= $air_temp_1.$air_temp_2 + $air_temp_3;
	$air_temp_low	= $air_temp_1.$air_temp_2 - $air_temp_3;
	
	
	// convert to c if in f
	if ($units_degrees_format == 'f') {
		
		$air_temp_high_c 	= ($air_temp_high - 32) / 1.8;
		$air_temp_low_c 	= ($air_temp_low  - 32) / 1.8;
		
	} else {
		$air_temp_high_c 	= $air_temp_high;
		$air_temp_low_c 	= $air_temp_low;
	}
	
	
	if ($air_temp >= $air_temp_low_c && $air_temp <= $air_temp_high_c) {
		
		$sql = "SELECT value_$air_function,length_$air_function,delay_$air_function FROM rf WHERE id = '$air_port'";
		$results = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($results);
		
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
		
	}

}