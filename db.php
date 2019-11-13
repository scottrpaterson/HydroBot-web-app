<?php

$conn = mysqli_connect('localhost', 'root', 'hydrobot', 'hydrobot');

if (mysqli_connect_error()) {
	echo "<p>Error in connection to database.</p>";
	exit();
}