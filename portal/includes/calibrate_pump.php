<?php
session_start();

// exit if no session
if (!isset($_SESSION['login'])) {
   header("Location: ../index.php");
   exit;
}

// get data

$cal-measuring-amount 	= $_GET['cal-measuring-amount'];
$cal-actual				= $_GET['cal-actual'];
$cal-type 				= $_GET['cal-type'];


$sql = "INSERT INTO settings (setting,value,value2) VALUES ('pump','$timezone[0]','$timezone[1]')";

if ($conn->query($sql) === TRUE) {
	$save = true;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}



if (empty($cal)) {
	$cal = 'success';
}

$response = array(
	'cal'      => $cal,

);

echo json_encode($response);

exit();