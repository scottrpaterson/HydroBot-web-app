<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Settings</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	echo "<ul>";
		echo "<li><a href='rf_add.php'>Add a new RF standard outlet</a></li>";
		echo "<li><a href='rf_add_variable.php'>Add a new variable RF speed controller</a></li>";
		echo "<li><a href='rf_delete.php'>Delete a RF outlet / speed controller</a></li>";
		echo "<br />";
		echo "<li><a href='timezone.php'>Change Timezone</a></li>";
		echo "<li><a href='units.php'>Change Units</a></li>";
		echo "<br />";
		echo "<li><a href='calibrate.php'>Calibrate Sensors</a></li>";
		echo "<li><a href='prime.php'>Prime Peristaltic Pumps</a></li>";
		echo "<br />";
		echo "<li><a href='account.php'>Account</a></li>";
		echo "<li><a href='dev.php'>Administration Tools</a></li>";
	echo "</ul>";

	echo "<br />";

	echo "<small>Portal version: $version</small>";

	echo "</div>";
echo "</div>";

include_once ('footer.php');