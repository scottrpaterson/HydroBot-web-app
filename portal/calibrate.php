<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Calibration</h5>";
	echo "</div>";
	echo "<div class='box-body'>";
	
echo "<table>";
	echo "<tr><td> <a class='button' href='calibrate_ph.php'>pH Calibration</a></td></tr>";
	echo "<tr><td> <a class='button' href='calibrate_ec.php'>EC Calibration</a></td></tr>";
	echo "<tr><td> <a class='button' href='calibrate_temp.php'>Temperature Calibration</a></td></tr>";

	echo "</table>";

	echo "</div>";
echo "</div>";



include_once ('footer.php');