<?php

// get data

if (isset($_POST["json"])) {
	$json = $_POST["json"];
} else {
	$json = false;
}


include_once('/var/www/html/db.php');


// Get temperature units
$sql = "SELECT value FROM settings WHERE setting = 'units_degrees_format'";
$settings_results = $conn->query($sql);

list($units_degrees_format) = mysqli_fetch_row($settings_results);

$units_degrees_format = ucfirst($units_degrees_format);




//$cpu_temp  	= exec("cat /sys/class/thermal/thermal_zone0/temp 2>&1") / 1000;
//$gpu_temp = exec("vcgencmd measure_temp 2>&1");


$water_ph 	= exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,10' r 2>&1");
$water_ec 	= exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,11' r 2>&1");
$water_temp = exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,12' r 2>&1");
$air		= exec("python2 /var/www/html/portal/includes/get_temp.py 2>&1");

if (isset($_POST["json"])) {
	$uptime 	= exec("uptime -p");
}


// get timezone from db and set it to the php default
$sql = "SELECT value2 FROM settings WHERE setting = 'timezone'";
$settings_results = $conn->query($sql);
list($interval) = mysqli_fetch_row($settings_results);
date_default_timezone_set("$interval");

if ($json == true) {
	$date 		= date("M d, Y g:i:s a");
}


// calculations
//$gpu_temp 	= explode('=', $gpu_temp);
//$gpu_temp 	= explode("'C", $gpu_temp[1]);
$air 		= explode('|', $air);
if (isset($_POST["json"])) {
	$uptime 	= explode('up ', $uptime);
}
if ($water_temp < 0) {
	$water_temp = 0;
}



if ($units_degrees_format == 'F') {
	//$cpu_temp_converted        = convert_to_f($cpu_temp);
	$air_temp_converted        = convert_to_f($air[0]);
	$water_temp_converted      = convert_to_f($water_temp);

}


if ($json == true) {
	$response = array(
		//'cpu_temp'      => round_value($cpu_temp_converted),
		//'gpu_temp' 	=> round_value($gpu_temp[0])."C (".convert_to_f($gpu_temp[0])."F)",
		'uptime'     	=> $uptime[1],
		'air_temp'      => round_value($air_temp_converted),
		'air_hum'       => $air[1],
		'water'     	=> round_value($water_temp_converted),
		'ph'     		=> $water_ph,
		'ec'     		=> $water_ec,
		'date'     		=> $date,
	);

	echo json_encode($response);

	exit();

} else {

	$uptime = exec("awk '{print $0/60;}' /proc/uptime");

	$uptime = explode(".",$uptime);

    // always save temperatures in C format
	$uptime 	= $uptime[0];
	$air_temp   = $air[0];
	$air_hum 	= $air[1];
	$water 		= round_value($water_temp);
	$ph 		= $water_ph;
	$ec 		= $water_ec;
	$date 		= date("Y-m-d H:i:s");
}





function convert_to_f($c) {
	return $f = ($c * 9/5) + 32;
}

function round_value($value) {
	return round($value, 2);
}
