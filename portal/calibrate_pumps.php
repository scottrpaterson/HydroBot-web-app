<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Calibrate Pumps</h5>";
	echo "</div>";
	echo "<div class='box-body'>";
	
echo "<table>";
	echo "<tr><td> <a class='button' href='calibrate_pumps_up.php'>Calibrate pH up pump</a></td></tr>";
	echo "<tr><td> <a class='button' href='calibrate_pupms_down.php'>Calibrate pH down pump</a></td></tr>";
	echo "<tr><td> <a class='button' href='settings.php'>Back</a></td></tr>";
	echo "</table>";

	echo "</div>";
echo "</div>";



include_once ('footer.php');