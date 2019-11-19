<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Calibrate ph up pump</h5>";
	echo "</div>";
	echo "<div class='box-body'>";	
	
	echo "<br />";
	
	echo "Before pressing run:";
	echo "<ol>";
	echo "<li>Please choose a measuring device. Most kitchen measuring cups start at 100 mL.</li>";
	echo "<li>Make sure the pump tubing is fully primed.</li>";
	echo "<li>Make sure the suction tube is placed in water and the discharge tube is placed in the measuring device.</li>";
	echo "</ol>";
	
	echo "<div class='cal-measuring-amount-div'>";
		echo "<select id='cal-measuring-amount'>";
			echo "<option value='30'>30 mL</option>";
			echo "<option value='100'>100 mL</option>";
		echo "</select>";
		
		echo "<br /><br />";
		
		echo "<a href='#' class='button cal-pump'>Run</a>";
	
	echo "</div>";
	
	echo "<div class='cal-actual-div' style='display:none;'>";
		echo "Please wait until the pump finishes running.";
		echo "<br />";
		echo "How many mL were actually dispensed: ";
		echo "<input type='text' id='cal-actual-amount' size='4'>";
		echo "<input type='hidden' id='cal-type' value='up'>";
		echo "<br /><br />";
		echo "<a href='#' class='button cal-pump-up-save'>Save</a>";
	echo "</div>";
	
	echo "<div id='4' style='display:none;'>Calibration complete. <br /><br /><a href='calibrate_pumps.php' class='button'>Back</a></div>";

	echo "</div>";
echo "</div>";



include_once ('footer.php');