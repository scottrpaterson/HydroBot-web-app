<?php

include_once ('header.php');

echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Factory Reset</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	echo "Click the button below to factory reset the device.";

	echo "<br /><br />This will delete all settings, data, logs, accounts, and wifi settings. It cannot be undone.";
	
	echo "<br /><br />Resetting will take about 2 minutes and after it's finished you will be able to connect to the wifi network name 'HydroBot Setup'.";
	
	echo "<br /><br />After clicking the button below, you can close this window.";

	echo "<br /><br />";
	
	echo "<a class='button save' id='factory_reset'>Factory Reset</a>";


	echo "</div>";
echo "</div>";



include_once ('footer.php');
