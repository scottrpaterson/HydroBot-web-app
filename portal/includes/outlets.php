<?php

function filter_login_input($loginData) {
	$loginData = trim($loginData);
	$loginData = stripslashes($loginData);
	$loginData = htmlspecialchars($loginData);
	return $loginData;
}
	

$value 	= filter_login_input($_POST["value"]);
$length = filter_login_input($_POST["length"]);
$delay 	= filter_login_input($_POST["delay"]);
$id 	= filter_login_input($_POST["id"]);
$idmain = filter_login_input($_POST["id-main"]);

// get data
$result = exec("python2 /var/www/html/portal/includes/outlets.py $value $length $delay 2>&1");


$response = array(
	'value'         	=> $result,
	'id'         		=> $id,
	'idmain'         	=> $idmain,
);

echo json_encode($response);

exit();