<?php

include_once('/var/www/html/db.php');




// check ph values

$water_ph 	= exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,10' 2>&1");




// check db set point value
$sql = "SELECT value, value2 FROM settings WHERE setting = 'ph_set'";
$ph_set_results = $conn->query($sql);

list($ph_set,$ph_hold_1) = mysqli_fetch_row($ph_set_results);

$sql = "SELECT value, value2 FROM settings WHERE setting = 'ph_set_2'";
$ph_set_results = $conn->query($sql);

list($ph_hold_2,$ph_hold_3) = mysqli_fetch_row($ph_set_results);



// calculate high and low values

$high_value = $ph_hold_1.'.'.$ph_hold_2 + $ph_hold_3;
$low_value 	= $ph_hold_1.'.'.$ph_hold_2 - $ph_hold_3;


// set outer limits
if ($water_ph < 1 || $water_ph > 15) {
	$water_ph = '';
}




// add ph up
if ($water_ph < $low_value) {
	exec("python2 /var/www/html/portal/includes/ph_controller.py 'input_motor_ph_up' '500' '255' 2>&1");
}

// add ph down
if ($water_ph > $high_value) {
	exec("python2 /var/www/html/portal/includes/ph_controller.py 'input_motor_ph_down' '500' '255' 2>&1");
}