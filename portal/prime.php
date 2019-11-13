<?php

include_once ('header.php');

echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Prime</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	echo "Pressing the buttons below will run the pumps for 10 seconds at a time.";

	echo "<br /><br />";

	echo "<table><tr><td>";
		echo "<input type='button' class='button prime-pumps' id='up' data-function='up' data-length='15000' value='Prime pH up'>";
	echo "</td><td>";
		echo "<input type='button' class='button prime-pumps' id='down' data-function='down' data-length='15000' value='Prime pH down'>";
	echo "</td></tr></table>";

	echo "<br />";

	echo "Note: Make sure the tubes going into the reservoir do not go below the water line.";



	echo "</div>";
echo "</div>";



include_once ('footer.php');
