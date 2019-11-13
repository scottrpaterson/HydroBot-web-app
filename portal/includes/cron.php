<?php


// cron file location - /var/spool/cron/crontabs/www-data

$value 	= 	$argv[3];
$length = 	$argv[4];
$delay 	= 	$argv[5];

function filter_login_input($loginData) {
	$loginData = trim($loginData);
	$loginData = stripslashes($loginData);
	$loginData = htmlspecialchars($loginData);
	return $loginData;
}
	

$value 	= filter_login_input($value);
$length = filter_login_input($length);
$delay 	= filter_login_input($delay);

// get data
$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");