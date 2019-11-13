<?php
session_start();

// exit if no session
if (!isset($_SESSION['login'])) {
   header("Location: ../index.php");
   exit;
}

// get data

$address 	= $_GET['address'];
$solution 	= $_GET['solution'];

if (isset($_GET['clear'])) {
	$clear 	= $_GET['clear'];
} else {
	$clear  = '';
}


if ($clear == '1') {
	exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,$address' cal,clear, 2>&1");
	$cal = '';
} else {
	$cal 	= exec("python2 /var/www/html/portal/Atlas-Scientific/i2c.py 'address,$address' cal,$solution, 2>&1");
	}

if (empty($cal)) {
	$cal = 'success';
}

$response = array(
	'cal'      => $cal,

);

echo json_encode($response);

exit();