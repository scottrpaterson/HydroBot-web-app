<?php

include_once ('header.php');

echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Reset Wifi</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	echo "Click the button below to reset the wifi and put the device back into wireless ad-hoc mode.";


	echo "<form name='reset_wifi' method='post' action='reset_wifi.php'>";

	echo "<br /><br />";
	echo "<a class='button save' id='reset_wifi'>Reset</a>";




	echo "</div>";
echo "</div>";



include_once ('footer.php');
