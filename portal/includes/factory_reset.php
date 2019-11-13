<?php
session_start();

// exit if no session
if (!isset($_SESSION['login'])) {
   header("Location: ../index.php");
   exit;
}


include_once('/var/www/html/db.php');
include_once '/var/www/html/portal/includes/cron_library.php';


// reset account
$sql = "DELETE FROM account";

if ($conn->query($sql) === TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

// reset log
$sql = "DELETE FROM log";

if ($conn->query($sql) === TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

// reset rf
$sql = "DELETE FROM rf";

if ($conn->query($sql) === TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}


// reset settings
$sql = "DELETE FROM settings";

if ($conn->query($sql) === TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

// delete all crons
$cron = new \Crontab\Crontab();

// remove all crons
$cron->clear();


// reset wifi
$output 	= exec("python3 reset_wifi.py  2>&1");



$response = array(
	'msg'     		=> 'success',
);

echo json_encode($response);

session_destroy();


// restart
exec("python3 restart.py  2>&1");