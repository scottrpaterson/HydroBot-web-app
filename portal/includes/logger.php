<?php

include_once('/var/www/html/portal/includes/get_stats.php');	
	
$sql = "INSERT INTO log (date, air_temp, air_hum, water_temp, water_ph, water_ec, uptime) VALUES ( '$date', '$air_temp', '$air_hum', '$water', '$ph', '$ec', '$uptime')";


if ($conn->query($sql) === TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}