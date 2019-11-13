<?php


include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Update</h5>";
	echo "</div>";
	echo "<div class='box-body'>";
	
	
	echo "<table style='width:350px;'>";
	echo "<tr><td>Your version is: </td><td>$version</td></tr>";
	
	
	
	

	
    $string = file_get_contents("https://hydro.bot/files/readme.txt");

    if($string === FALSE) {
    }

	echo "<tr><td>Latest version is: </td><td>$string</td></tr>";
	
	echo "<tr><td colspan='2'><br /></td></tr>";
	echo "<tr><td colspan='2'>";
	
	if ($version == $string) {
		echo "Your software is up to date.";
	} else {
		echo "<b>A new version is available. Please update.</b>";
		
		echo "<br /><br />";
		
		echo "<a class='button' href='/updater.php?run_update=true'>Update HydroBot Portal</a>";
	}
	
	echo "</td></tr>";
	
	echo "</table>";
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


	echo "</div>";
echo "</div>";



include_once ('footer.php');
