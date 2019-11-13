<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>EC Calibration</h5>";
	echo "</div>";
	echo "<div class='box-body'>";	
	
	echo "<br />";
	
	
	echo "<a href='calibrate_ec_single.php' class='button'>Calibrate using one point calibration.</a>";
	echo "<br /><br />";	
	echo "<a href='calibrate_ec_two.php' class='button'>Calibration using two point calibration (low and high).</a>";
	
	echo "</div>";
echo "</div>";



include_once ('footer.php');