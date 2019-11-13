<?php

include_once ('header.php');



echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Change Timezone</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	// save
	if (isset($_POST["timezone_save"])) {
		
		$timezone = filter_login_input($_POST['timezone']);
		
		$timezone = explode("|",$timezone);
		
		$sql = "DELETE FROM settings WHERE setting = 'timezone'";
		
		if ($conn->query($sql) === TRUE) {
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
		$sql = "INSERT INTO settings (setting,value,value2) VALUES ('timezone','$timezone[0]','$timezone[1]')";
		
		if ($conn->query($sql) === TRUE) {
			$save = true;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
        
        header("Refresh:0");
		
	}

	// get existing values from db
	$sql = "SELECT value2 FROM settings WHERE setting = 'timezone'";
	$settings_results = $conn->query($sql);

	//echo $settings_results['interval'];
	list($interval) = mysqli_fetch_row($settings_results);

	echo "<form name='timezone' method='post' action='timezone.php'>";
	?>


	<select name="timezone" >
		<?php
		$o = get_timezones();
		
		foreach($o as $tz => $label)
		{
			echo "<option "; if ($interval == $tz) { echo " SELECTED "; } echo "value='$label|$tz'>$tz [$label]</option>";
		}
		?>
    </select>
	
	<br /><br />
	The current time is: 
	<?php

    date_default_timezone_set($timezone_offset);

	echo $date = date('m/d/Y h:i:s a', time());
	?>

	<br /><br />

	<?php

	if (isset($save)) {
		$save_text = "Saved. Loading...";
	} else {
		$save_text = "Save";
	}
		


	echo "<input type='hidden' name='timezone_save'>";
	echo "<input class='button save' type='submit' value='$save_text'>";
	echo "</form>";




	echo "</div>";
echo "</div>";



include_once ('footer.php');
