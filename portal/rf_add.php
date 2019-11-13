<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Add a new on / off RF outlet</h5>";
	echo "</div>";
	echo "<div class='box-body'>";
	
	
// save
if (isset($_POST["rf_save"])) {

	$outlet 	= filter_login_input($_POST["outlet"]);
	
	$value_0 	= filter_login_input($_POST["value_0"]);
	$length_0 	= filter_login_input($_POST["length_0"]);
	$delay_0	= filter_login_input($_POST["delay_0"]);
	
	$value_1 	= filter_login_input($_POST["value_1"]);
	$length_1 	= filter_login_input($_POST["length_1"]);
	$delay_1 	= filter_login_input($_POST["delay_1"]);

	$sql = "INSERT INTO rf (name,value_0,length_0,delay_0,value_1,length_1,delay_1) VALUES ('$outlet','$value_0','$length_0','$delay_0','$value_1','$length_1','$delay_1')";

if ($conn->query($sql) === TRUE) {
    echo "Outlet saved successfully.";
	echo "<br /><br />";
	echo "<a class='button' href='rf_add.php'>Add another outlet</a>";
	echo "<br /><br />";
	echo "<a class='button' href='settings.php'>Settings</a>";
	exit;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
	
	
}

echo "<form name='rf' method='post' action='rf_add.php'>";

	echo "Give this outlet a name: ";
	echo "<input type='text' name='outlet'> Example: Outlet 1";
	
	echo "<input type='hidden' id='value_0' name='value_0'>";
	echo "<input type='hidden' id='length_0' name='length_0'>";
	echo "<input type='hidden' id='delay_0' name='delay_0'>";
	
	echo "<input type='hidden' id='value_1' name='value_1'>";
	echo "<input type='hidden' id='length_1' name='length_1'>";
	echo "<input type='hidden' id='delay_1' name='delay_1'>";
	
	
	echo "<br /><br />";
	echo "<span id='status'></span>";
	echo "<br /><br />";
	
	echo "<a href='#' id='listen_0' class='button'>Click this link and hold down the off button for 10 seconds.</a>";
	echo "<a href='#' id='listen_1' style='display:none;' class='button'>Click this link and hold down the on button for 10 seconds.</a>";

	echo "<br /><br />";

	echo "<input type='hidden' name='rf_save'>";
	echo "<input type='submit' id='save' value='Save' style='display:none;' class='button'>";
echo "</form>";


	echo "</div>";
echo "</div>";



include_once ('footer.php');