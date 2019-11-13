<?php

include_once('/var/www/html/db.php');




// check hum values

$air 		= exec("python2 /var/www/html/portal/includes/get_temp.py 2>&1");
$air 		= explode('|', $air);
$hum		= $air[1];




// check db set point value
$sql = "SELECT value, value2 FROM settings WHERE setting = 'hum_set'";
$hum_set_results = $conn->query($sql);

list($hum_set,$hum_hold_1) = mysqli_fetch_row($hum_set_results);

$sql = "SELECT value, value2 FROM settings WHERE setting = 'hum_set_2'";
$hum_set_results = $conn->query($sql);

list($hum_hold_2,$hum_hold_3) = mysqli_fetch_row($hum_set_results);



// calculate high and low values
$hum_high 	= $hum_hold_1.$hum_hold_2 + $hum_hold_3;
$hum_low	= $hum_hold_1.$hum_hold_2 - $hum_hold_3;


// set outer limits
if ($hum < 0 || $hum > 100) {
	$hum = '';
}



// get outlets



// turn on hum hum(s)
if ($hum_temp < $hum_low) {
	
	// get outlets
	$sql = "SELECT value FROM settings WHERE setting = 'hum_outlets'";
	$hum_outlets = $conn->query($sql);
	
	foreach ($hum_outlets as $key => $value) {
		
		$value = $value['value'];
		
		// get outlet codes
		$sql = "SELECT value_1, length_1, delay_1 FROM rf WHERE id = '$value'";
		$hum_outlet = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($hum_outlet);
		
		// turn on outlet
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
		echo "a";
	}
}


// turn on hum dehum(s)
if ($hum_temp > $hum_high) {
	
	// get outlets
	$sql = "SELECT value FROM settings WHERE setting = 'dehum_outlets'";
	$dehum_outlets = $conn->query($sql);
	
	foreach ($dehum_outlets as $key => $value) {
		
		$value = $value['value'];
		
		// get outlet codes
		$sql = "SELECT value_1, length_1, delay_1 FROM rf WHERE id = '$value'";
		$hum_outlet = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($hum_outlet);
		
		// turn on outlet
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
	}
}


// turn off hum or dehum
if ($hum_temp >= $hum_low || $hum_temp <= $hum_high) {
	
	// get outlets
	$sql = "SELECT value FROM settings WHERE setting = 'hum_outlets' OR setting = 'dehum_outlets'";
	$dehum_outlets = $conn->query($sql);
	
	foreach ($dehum_outlets as $key => $value) {
		
		$value = $value['value'];
		
		// get outlet codes
		$sql = "SELECT value_0, length_0, delay_0 FROM rf WHERE id = '$value'";
		$hum_outlet = $conn->query($sql);
		list($value,$length,$delay) = mysqli_fetch_row($hum_outlet);
		
		// turn off outlet
		$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");
	}
}