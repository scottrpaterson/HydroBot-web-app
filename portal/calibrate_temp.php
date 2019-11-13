<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Temperature Calibration</h5>";
	echo "</div>";
	echo "<div class='box-body'>";	
	
	echo "<br />";
	
	echo "<a href='#' data-address='12' data-clear='1' data-id='0' id='0' class='button cal-temp'>Press this button to clear the current calibration settings.</a>";
	
	echo "<div id='1' style='display:none;'>";
		echo "Temperature: <input type='text' id='temp'> $units_degrees_format<br /><br />";
		echo "<input type='hidden' id='unit' value='$units_degrees_format'>";
		echo "<a href='#' data-address='12' data-id='1' class='button cal-temp'>Calibrate</a>";
	echo "</div>";
	
	echo "<a href='#' id='wait' style='display:none;background: #434343;' class='button'>Please wait...</a>";
	echo "<div id='2' style='display:none;'>Calibration complete. <br /><br /><a href='calibrate.php' class='button'>Back</a></div>";

	echo "</div>";
echo "</div>";



include_once ('footer.php');