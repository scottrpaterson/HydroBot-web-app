<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>pH Calibration</h5>";
	echo "</div>";
	echo "<div class='box-body'>";	
	
	echo "<br />";
	
	echo "<a href='#' data-address='10' data-solution='' data-clear='1' data-id='0' id='0' class='button cal-ph'>Press this button to clear the current calibration settings.</a>";
	echo "<a href='#' data-address='10' data-solution='mid,7.00' data-id='1' id='1' style='display:none;' class='button cal-ph'>Put the pH probe in 7.00 calibration solution, wait 10 seconds, and click this button.</a>";
	echo "<a href='#' data-address='10' data-solution='low,4.00' data-id='2' id='2' style='display:none;' class='button cal-ph'>Put the pH probe in 4.00 calibration solution, wait 10 seconds, and click this button.</a>";
	echo "<a href='#' data-address='10' data-solution='high,10.00' data-id='3' id='3' style='display:none;' class='button cal-ph'>Put the pH probe in 10.00 calibration solution, wait 10 seconds, and click this button.</a>";
	echo "<a href='#' id='wait' style='display:none;background: #434343;' class='button'>Please wait...</a>";
	echo "<div id='4' style='display:none;'>Calibration complete. <br /><br /><a href='calibrate.php' class='button'>Back</a></div>";

	echo "</div>";
echo "</div>";



include_once ('footer.php');