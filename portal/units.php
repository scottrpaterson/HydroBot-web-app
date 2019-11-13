<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Change Units</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	// save
	if (isset($_POST["units_save"])) {
		
		$units_degrees_format = filter_login_input($_POST['units_degrees_format']);
		
		$sql = "DELETE FROM settings WHERE setting = 'units_degrees_format'";
		
		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
		$sql = "INSERT INTO settings (setting,value) VALUES ('units_degrees_format','$units_degrees_format')";
		
		if ($conn->query($sql) === TRUE) {
			$save = true;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
	}

	echo "<form method='post' action='units.php'>";

	echo "<select name='units_degrees_format'>";
		echo "<option "; if ($units_degrees_format == 'F') { echo "SELECTED"; } echo " value='F'>Fahrenheit</option>";
		echo "<option "; if ($units_degrees_format == 'C') { echo "SELECTED"; } echo " value='C'>Celsius</option>";
	echo "</select><br /><br />";


	if (isset($save)) {
		$save_text = "Saved";
	} else {
		$save_text = "Save";
	}





	echo "<input type='hidden' name='units_save'>";
	echo "<input class='button save' type='submit' value='$save_text'>";
	echo "</form>";


	echo "</div>";
echo "</div>";



include_once ('footer.php');




