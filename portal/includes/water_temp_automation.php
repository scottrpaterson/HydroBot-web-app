<?php

include_once('/var/www/html/db.php');




// check ph values

$water_temp = exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,12' 2>&1");




// check db set point value
$sql = "SELECT value, value2 FROM settings WHERE setting = 'water_set'";
$water_set_results = $conn->query($sql);

list($water_set,$water_hold_1) = mysqli_fetch_row($water_set_results);

$sql = "SELECT value, value2 FROM settings WHERE setting = 'water_set_2'";
$water_set_results = $conn->query($sql);

list($water_hold_2,$water_hold_3) = mysqli_fetch_row($water_set_results);


// get temp units
$sql = "SELECT value FROM settings WHERE setting = 'units_degrees_format'";
$settings_results = $conn->query($sql);

list($units_degrees_format) = mysqli_fetch_row($settings_results);



// calculate high and low values
$water_temp_high = $water_hold_1.$water_hold_2 + $water_hold_3;
$water_temp_low	 = $water_hold_1.$water_hold_2 - $water_hold_3;



// set outer limits
if ($water_temp < 0 || $water_temp > 65) {
	$water_temp = '';
}



// convert to c if in f
if ($units_degrees_format == 'f') {
	
	$water_temp_high_c 	= ($water_temp_high - 32) / 1.8;
	$water_temp_low_c 	= ($water_temp_low  - 32) / 1.8;
	
} else {
	$water_temp_high_c 	= $water_temp_high;
	$water_temp_low_c 	= $water_temp_low;
}

// get outlets



// turn on water heater(s)
if ($water_temp < $water_temp_low_c) {
	
	// get outlets
	$sql = "SELECT value FROM settings WHERE setting = 'heater_outlets'";
	$heater_outlets = $conn->query($sql);
	
	foreach ($heater_outlets as $key => $value) {
		
		$value = $value['value'];
		
		// get outlet codes
		$sql = "SELECT value_1, length_1, delay_1 FROM rf WHERE id = '$value'";
		$heater_outlet = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($heater_outlet);
		
		// turn on outlet
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
		echo "a";
	}
}


// turn on water chillers(s)
if ($water_temp > $water_temp_high_c) {
	
	// get outlets
	$sql = "SELECT value FROM settings WHERE setting = 'chiller_outlets'";
	$chiller_outlets = $conn->query($sql);
	
	foreach ($chiller_outlets as $key => $value) {
		
		$value = $value['value'];
		
		// get outlet codes
		$sql = "SELECT value_1, length_1, delay_1 FROM rf WHERE id = '$value'";
		$heater_outlet = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($heater_outlet);
		
		// turn on outlet
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
	}
}


// turn off heater or chiller
if ($water_temp >= $water_temp_low_c || $water_temp <= $water_temp_high_c) {
	
	// get outlets
	$sql = "SELECT value FROM settings WHERE setting = 'heater_outlets' OR setting = 'chiller_outlets'";
	$chiller_outlets = $conn->query($sql);
	
	foreach ($chiller_outlets as $key => $value) {
		
		$value = $value['value'];
		
		// get outlet codes
		$sql = "SELECT value_0, length_0, delay_0 FROM rf WHERE id = '$value'";
		$heater_outlet = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($heater_outlet);
		
		// turn off outlet
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
	}
}