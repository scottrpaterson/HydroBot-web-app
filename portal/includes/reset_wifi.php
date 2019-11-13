<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("Location: ../index.php");
   exit;
}

$output 	= exec("python3 reset_wifi.py  2>&1");


$response = array(
	'msg'     		=> $output,
);

echo json_encode($response);


// restart
exec("python3 restart.py  2>&1");