<?php
session_start();

// exit if no session
if (!isset($_SESSION['login'])) {
   header("Location: ../index.php");
   exit;
}

// get data
$result = exec("python2 /var/www/html/portal/includes/rf_listen.py 2>&1");

$result = 		explode('|', $result);

$response = array(
	'value'         	=> $result[0],
	'length'         	=> $result[1],
	'delay'         	=> $result[2],
);

echo json_encode($response);

exit();
