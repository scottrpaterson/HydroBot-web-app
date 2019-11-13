<?php
session_start();

// exit if no session
if (!isset($_SESSION['login'])) {
   header("Location: ../index.php");
   exit;
}


include_once('/var/www/html/db.php');


function filter_login_input($loginData) {
	$loginData = trim($loginData);
	$loginData = stripslashes($loginData);
	$loginData = htmlspecialchars($loginData);
	return $loginData;
}
	

$id 	= filter_login_input($_POST["id"]);

// delete rf outlet by id
$sql = "DELETE FROM rf WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

$response = array(
	'result'         	=> "success",
);

echo json_encode($response);

exit();