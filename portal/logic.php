<?php

include_once ('header.php');


// save
if (isset($_POST["logic_save"])) {

	include_once '/var/www/html/portal/includes/cron_library.php';

	$cron = new \Crontab\Crontab();

	// remove all crons
	$cron->clear();






	// get timezone offset
	$sql = "SELECT value FROM settings WHERE setting = 'timezone'";
	$timezone_results = $conn->query($sql);

	list($timezone) = mysqli_fetch_row($timezone_results);

	$timezone_sign 	= substr($timezone, 0, 1); // + or -
	$timezone 		= substr($timezone, 1);

	$timezone = explode(":",$timezone);


	// save cron jobs
	if (isset($_POST['port'])) {
		
		foreach ($_POST['port'] as $key => $value) {
			
			$hour_raw 	= $_POST['hour'][$key];
			$min_raw 	= $_POST['min'][$key];
			
			if ($timezone_sign == '-') {
                
                
				$hour 	= $hour_raw + $timezone[0];
				$min 	= $min_raw + $timezone[1];
				
				if ($hour > 23) {
					$hour = $hour - 23;
				}
				
			}
			
			
			$port =  	$_POST['port'][$key];
			$function = $_POST['function'][$key];
			
			$sql = "SELECT value_$function,length_$function,delay_$function FROM rf WHERE id = '$port'";
			$results = $conn->query($sql);
			list($value,$length,$delay) = mysqli_fetch_row($results);
			
			// used for uniqueness for each line
			$rand128_hex = bin2hex(openssl_random_pseudo_bytes(5));
			
			$cron->setHour($hour);
			$cron->setMinute($min);
			$cron->append("php /var/www/html/portal/includes/cron.php $port $function $value $length $delay $hour_raw $min_raw $rand128_hex");
			$cron->execute();
			
		}
		
	}











	// save setting values

	$settings = array();

	$settings['interval'] 						= filter_login_input($_POST['interval']);
	$settings['log']							= filter_login_input($_POST['log']);

	$settings['ph_set']							= filter_login_input($_POST['ph_set']);
	$settings['ph_hold_1']						= filter_login_input($_POST['ph_hold_1']);
	$settings['ph_hold_2'] 						= filter_login_input($_POST['ph_hold_2']);
	$settings['ph_hold_3'] 						= filter_login_input($_POST['ph_hold_3']);

	$settings['water_set'] 						= filter_login_input($_POST['water_set']);
	$settings['water_hold_1'] 					= filter_login_input($_POST['water_hold_1']);
	$settings['water_hold_2'] 					= filter_login_input($_POST['water_hold_2']);
	$settings['water_hold_3'] 					= filter_login_input($_POST['water_hold_3']);

	$settings['hum_set'] 						= filter_login_input($_POST['hum_set']);
	$settings['hum_hold_1'] 					= filter_login_input($_POST['hum_hold_1']);
	$settings['hum_hold_2'] 					= filter_login_input($_POST['hum_hold_2']);
	$settings['hum_hold_3'] 					= filter_login_input($_POST['hum_hold_3']);

	$settings['ph_set_point_interval'] 			= filter_login_input($_POST['ph_set_point_interval']);
	$settings['water_temp_set_point_interval'] 	= filter_login_input($_POST['water_temp_set_point_interval']);
	$settings['humidity_set_point_interval'] 	= filter_login_input($_POST['humidity_set_point_interval']);
	$settings['air_temp_set_point'] 			= filter_login_input($_POST['air_temp_set_point']);









	if ($settings['log'] == '1') {
		$cron->setHour('*');
		$cron->setMinute('*/'.$settings['interval']);
		$cron->append("php /var/www/html/portal/includes/logger.php");
		$cron->execute();
	}

	if ($settings['ph_set'] == '1') {
		$cron->setHour('*');
		$cron->setMinute('*/'.$settings['ph_set_point_interval']);
		$cron->append("php /var/www/html/portal/includes/ph_automation.php");
		$cron->execute();
	}

	if ($settings['water_set'] == '1') {
		$cron->setHour('*');
		$cron->setMinute('*/'.$settings['water_temp_set_point_interval']);
		$cron->append("php /var/www/html/portal/includes/water_temp_automation.php");
		$cron->execute();
	}

	if ($settings['hum_set'] == '1') {
		$cron->setHour('*');
		$cron->setMinute('*/'.$settings['humidity_set_point_interval']);
		$cron->append("php /var/www/html/portal/includes/humidity_automation.php");
		$cron->execute();
	}









	// save heater outlets

	$sql = "DELETE FROM settings WHERE setting = 'heater_outlets'";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}


	if (isset($_POST['heater_outlets'])) {
		foreach ($_POST['heater_outlets'] as $key => $value) {

			$sql = "INSERT INTO settings (setting,value) VALUES ('heater_outlets','$value')";

			if ($conn->query($sql) === TRUE) {
				$save = 'true';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

		}
	}


	// save chiller outlets

	$sql = "DELETE FROM settings WHERE setting = 'chiller_outlets'";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}


	if (isset($_POST['chiller_outlets'])) {
		foreach ($_POST['chiller_outlets'] as $key => $value) {

			$sql = "INSERT INTO settings (setting,value) VALUES ('chiller_outlets','$value')";

			if ($conn->query($sql) === TRUE) {
				$save = 'true';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

		}
	}


	// save hum outlets

	$sql = "DELETE FROM settings WHERE setting = 'hum_outlets'";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}


	if (isset($_POST['hum_outlets'])) {
		foreach ($_POST['hum_outlets'] as $key => $value) {

			$sql = "INSERT INTO settings (setting,value) VALUES ('hum_outlets','$value')";

			if ($conn->query($sql) === TRUE) {
				$save = 'true';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

		}
	}




	// save dehum outlets

	$sql = "DELETE FROM settings WHERE setting = 'dehum_outlets'";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}


	if (isset($_POST['dehum_outlets'])) {
		foreach ($_POST['dehum_outlets'] as $key => $value) {

			$sql = "INSERT INTO settings (setting,value) VALUES ('dehum_outlets','$value')";

			if ($conn->query($sql) === TRUE) {
				$save = 'true';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

		}
	}



	// save air set point

	$sql = "DELETE FROM settings WHERE setting = 'air_temp_set_point'";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}


	if (isset($_POST['air_port'])) {
		$air_port_counter = '1';
		foreach ($_POST['air_port'] as $key => $value) {

			$air_settings = array();

			$air_settings['air_port'] 		= filter_login_input($_POST['air_port'][$key]);
			$air_settings['air_function'] 	= filter_login_input($_POST['air_function'][$key]);
			$air_settings['air_temp_1'] 	= filter_login_input($_POST['air_temp_1'][$key]);
			$air_settings['air_temp_2'] 	= filter_login_input($_POST['air_temp_2'][$key]);
			$air_settings['air_temp_3'] 	= filter_login_input($_POST['air_temp_3'][$key]);

			$air_settings = serialize($air_settings);

			$sql = "INSERT INTO settings (setting,value) VALUES ('air_temp_set_point','$air_settings')";

			if ($conn->query($sql) === TRUE) {
				$save = 'true';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

			$air_port_counter++;
		}


		if ($air_port_counter >= '1') {
			$cron->setHour('*');
			$cron->setMinute('*/'.$settings['air_temp_set_point']);
			$cron->append("php /var/www/html/portal/includes/air_temp_automation.php");
			$cron->execute();
		}
	}






	// update settings
	
	$sql = "DELETE FROM settings WHERE setting = 'automation_settings'";

	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}	
	
	$settings = serialize($settings);

	$sql = "INSERT INTO settings (setting,value) VALUES ('automation_settings','$settings')";


	if ($conn->query($sql) === TRUE) {
		$save = 'true';
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}



	$save = true;


}













//////////////////////






















	// get settings
	$sql = "SELECT value FROM settings WHERE setting = 'automation_settings'";
	$automation_settings_results = $conn->query($sql);
	
	if (mysqli_num_rows($automation_settings_results)>0) {
		
		list($automation_settings) = mysqli_fetch_row($automation_settings_results);
		
		$automation_settings = unserialize($automation_settings);
		extract($automation_settings);
		
	} else {
		
		$log 		= '';
		$interval 	= '';
		$ph_set 	= '';
		$ph_hold_1 	= '';
		$ph_hold_2 	= '';
		$ph_hold_3 	= '';
		$hum_set 	= '';
		$hum_hold_1 = '';
		$hum_hold_2 = '';
		$hum_hold_3 = '';
		$humidity_set_point_interval 	= '';
		$water_temp_set_point_interval 	= '';
		$ph_set_point_interval 			= '';
		$water_set 			= '';
		$water_hold_1 		= '';
		$water_hold_2 		= '';
		$water_hold_3 		= '';
		$air_temp_set_point = '';
		
	}

	echo "<form autocomplete='off' name='logic' method='post' action='logic.php'>";
    
    // get existing values from cron
    exec("crontab -l ", $output);






    //////////////////////
    echo "<div class='logic-main-container'>";



	echo "<div class='box box-main'>";
		echo "<div class='box-header'>";
			echo "<h5>Data Logger</h5>";
		echo "</div>";
		echo "<div class='box-body two-top'>";


			echo "<table>";
			echo "<tr><td>Logger: </td><td>";
			echo "<select name='log'>";
			echo "<option "; if ($log == '0') { echo "SELECTED"; } echo " value='0'>Off</option>";
			echo "<option "; if ($log == '1') { echo "SELECTED"; } echo " value='1'>On</option>";
			echo "</select></td></tr>";

			echo "<tr><td>Runs every: </td><td>";
			echo "<input name='interval' type='text' size='2' value='"; echo $interval; echo "'> minutes if enabled";
			echo "</td></tr>";
			echo "</table>";

			echo "</div>";
	echo "</div>";





    //////////////////////





   echo "<div class='box box-main'>";
		echo "<div class='box-header'>";
			echo "<h5>pH Set point</h5>";
		echo "</div>";
		echo "<div class='box-body two-top'>";




		echo "<table>";
		echo "<tr><td>Set Point: </td><td>";
		echo "<select name='ph_set'>";
		echo "<option "; if ($ph_set == '0') { echo "SELECTED"; } echo " value='0'>Off</option>";
		echo "<option "; if ($ph_set == '1') { echo "SELECTED"; } echo " value='1'>On</option>";
		echo "</select></td></tr>";

		echo "<tr><td>Runs every: </td><td><input type='text' name='ph_set_point_interval' value='$ph_set_point_interval' size='2'> minutes if enabled</td></tr>";

		echo "<tr><td><br /></td></tr>";

		echo "<tr><td>Hold at: </td><td>";


		echo "<select name='ph_hold_1'>";
			echo "<option "; if ($ph_hold_1 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($ph_hold_1 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($ph_hold_1 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($ph_hold_1 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($ph_hold_1 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($ph_hold_1 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($ph_hold_1 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($ph_hold_1 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($ph_hold_1 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
			echo "<option "; if ($ph_hold_1 == '10') { echo "SELECTED"; } echo " value='10'>10</option>";
			echo "<option "; if ($ph_hold_1 == '11') { echo "SELECTED"; } echo " value='11'>11</option>";
			echo "<option "; if ($ph_hold_1 == '12') { echo "SELECTED"; } echo " value='12'>12</option>";
			echo "<option "; if ($ph_hold_1 == '13') { echo "SELECTED"; } echo " value='13'>13</option>";
			echo "<option "; if ($ph_hold_1 == '14') { echo "SELECTED"; } echo " value='14'>14</option>";
		echo "</select>";

		echo ".";

		echo "<select name='ph_hold_2'>";
			echo "<option "; if ($ph_hold_2 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($ph_hold_2 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($ph_hold_2 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($ph_hold_2 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($ph_hold_2 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($ph_hold_2 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($ph_hold_2 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($ph_hold_2 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($ph_hold_2 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
		echo "</select>";

		echo " &#177; ";

		echo "<select name='ph_hold_3'>";
			echo "<option "; if ($ph_hold_3 == '.1') { echo "SELECTED"; } echo " value='.1'>.1</option>";
			echo "<option "; if ($ph_hold_3 == '.2') { echo "SELECTED"; } echo " value='.2'>.2</option>";
			echo "<option "; if ($ph_hold_3 == '.3') { echo "SELECTED"; } echo " value='.3'>.3</option>";
			echo "<option "; if ($ph_hold_3 == '.4') { echo "SELECTED"; } echo " value='.4'>.4</option>";
			echo "<option "; if ($ph_hold_3 == '.5') { echo "SELECTED"; } echo " value='.5'>.5</option>";
			echo "<option "; if ($ph_hold_3 == '.6') { echo "SELECTED"; } echo " value='.6'>.6</option>";
			echo "<option "; if ($ph_hold_3 == '.7') { echo "SELECTED"; } echo " value='.7'>.7</option>";
			echo "<option "; if ($ph_hold_3 == '.8') { echo "SELECTED"; } echo " value='.8'>.8</option>";
			echo "<option "; if ($ph_hold_3 == '.9') { echo "SELECTED"; } echo " value='.9'>.9</option>";
			echo "<option "; if ($ph_hold_3 ==  '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($ph_hold_3 == '1.1') { echo "SELECTED"; } echo " value='1.1'>1.1</option>";
			echo "<option "; if ($ph_hold_3 == '1.2') { echo "SELECTED"; } echo " value='1.2'>1.2</option>";
			echo "<option "; if ($ph_hold_3 == '1.3') { echo "SELECTED"; } echo " value='1.3'>1.3</option>";
			echo "<option "; if ($ph_hold_3 == '1.4') { echo "SELECTED"; } echo " value='1.4'>1.4</option>";
			echo "<option "; if ($ph_hold_3 == '1.5') { echo "SELECTED"; } echo " value='1.5'>1.5</option>";
			echo "<option "; if ($ph_hold_3 == '1.6') { echo "SELECTED"; } echo " value='1.6'>1.6</option>";
			echo "<option "; if ($ph_hold_3 == '1.7') { echo "SELECTED"; } echo " value='1.7'>1.7</option>";
			echo "<option "; if ($ph_hold_3 == '1.8') { echo "SELECTED"; } echo " value='1.8'>1.8</option>";
			echo "<option "; if ($ph_hold_3 == '1.9') { echo "SELECTED"; } echo " value='1.9'>1.9</option>";
			echo "<option "; if ($ph_hold_3 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
		echo "</select>";

		echo " pH";

		echo "</td></tr>";
		echo "</table>";

		echo "</div>";
	echo "</div>";







    //////////////////////


    echo "<div class='row'>";



    echo "<div class='box box-main'>";
		echo "<div class='box-header'>";
			echo "<h5>Humidity Set Point</h5>";
		echo "</div>";
		echo "<div class='box-body auto-height'>";

		echo "<table>";
		echo "<tr><td>Set Point: </td><td>";
		echo "<select name='hum_set'>";
		echo "<option "; if ($hum_set == '0') { echo "SELECTED"; } echo " value='0'>Off</option>";
		echo "<option "; if ($hum_set == '1') { echo "SELECTED"; } echo " value='1'>On</option>";
		echo "</select></td></tr>";

		echo "<tr><td>Runs every: </td><td> <input type='text' name='humidity_set_point_interval' value='$humidity_set_point_interval' size='2'> minutes if enabled</td></tr>";

		echo "<tr><td><br /></td></tr>";

		echo "<tr><td>Hold at: </td><td>";


		echo "<select name='hum_hold_1'>";
			echo "<option "; if ($hum_hold_1 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($hum_hold_1 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($hum_hold_1 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($hum_hold_1 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($hum_hold_1 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($hum_hold_1 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($hum_hold_1 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($hum_hold_1 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($hum_hold_1 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
		echo "</select>";


		echo "<select name='hum_hold_2'>";
			echo "<option "; if ($hum_hold_2 == '0') { echo "SELECTED"; } echo " value='0'>0</option>";
			echo "<option "; if ($hum_hold_2 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($hum_hold_2 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($hum_hold_2 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($hum_hold_2 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($hum_hold_2 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($hum_hold_2 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($hum_hold_2 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($hum_hold_2 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($hum_hold_2 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
		echo "</select> %";

		echo " &#177; ";

		echo "<select name='hum_hold_3'>";
			echo "<option "; if ($hum_hold_3 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($hum_hold_3 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($hum_hold_3 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($hum_hold_3 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($hum_hold_3 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($hum_hold_3 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($hum_hold_3 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($hum_hold_3 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($hum_hold_3 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
			echo "<option "; if ($hum_hold_3 ==  '10') { echo "SELECTED"; } echo " value='10'>10</option>";
		echo "</select>";


		echo "</td></tr>";
		echo "</table><br />";



        // hum outlets table
        echo "<table width='100%'><tr><td valign='top' width='50%'>";

        ////
        echo "Humidifier Outlets";


        echo "<table id='formtable_hum_outlets'>";
			echo "<tr><td></td></tr>";


			$sql = "SELECT value FROM settings WHERE setting = 'hum_outlets'";
			$hum_outlets = $conn->query($sql);

			foreach ($hum_outlets as $key => $value) {

				$key++;

				echo "<tr valign='top'><td class='counter_hum'>$key </td>

				<td> - <select name='hum_outlets[]'>";

				foreach ($rf_results as $key_a => $value_a) {
					echo "<option ";
						if ($value['value'] == $value_a['id']) { echo 'SELECTED'; } echo " value=".$value_a['id']." >".$value_a['name'];
					echo "</option>";
				}
				echo "</select>";

				echo "
				</td><td><a href='javascript:void(0);' class='remove_hum_outlet button'>Delete</a></td></tr>";

			}

		echo "</table>";

		echo "<table>";
		echo "<tr><td><br /><a class='button new_hum_outlet' href='#'>Add Outlet</a></td></tr>";
		echo "</td></tr></table>";


        echo "</td><td valign='top' width='50%'>";



        ////
        echo "Dehumidifier Outlets";


        echo "<table id='formtable_dehum_outlets'>";
			echo "<tr><td></td></tr>";


			$sql = "SELECT value FROM settings WHERE setting = 'dehum_outlets'";
			$dehum_outlets = $conn->query($sql);

			foreach ($dehum_outlets as $key => $value) {

				$key++;

				echo "<tr valign='top'><td class='counter_dehum'>$key </td>

				<td> - <select name='dehum_outlets[]'>";

				foreach ($rf_results as $key_a => $value_a) {
					echo "<option ";
						if ($value['value'] == $value_a['id']) { echo 'SELECTED'; } echo " value=".$value_a['id']." >".$value_a['name'];
					echo "</option>";
				}
				echo "</select>";

				echo "
				</td><td><a href='javascript:void(0);' class='remove_dehum_outlet button'>Delete</a></td></tr>";

			}

		echo "</table>";

		echo "<table>";
		echo "<tr><td><br /><a class='button new_dehum_outlet' href='#'>Add Outlet</a></td></tr>";
		echo "</td></tr></table>";


        // end hum outlets table
        echo "</td></tr></table>";



        echo "</div>";
	echo "</div>";





    //////////////////////





	echo "<div class='box box-main'>";
		echo "<div class='box-header'>";
			echo "<h5>Water Temperature Set Point</h5>";
		echo "</div>";
		echo "<div class='box-body auto-height'>";

		echo "<table>";
		echo "<tr><td>Set Point: </td><td>";
		echo "<select name='water_set'>";
		echo "<option "; if ($water_set == '0') { echo "SELECTED"; } echo " value='0'>Off</option>";
		echo "<option "; if ($water_set == '1') { echo "SELECTED"; } echo " value='1'>On</option>";
		echo "</select></td></tr>";

		echo "<tr><td>Runs every:</td><td> <input type='text' name='water_temp_set_point_interval' value='$water_temp_set_point_interval' size='2'> minutes if enabled</td></tr>";

		echo "<tr><td><br /></td></tr>";

		echo "<tr><td>Hold at: </td><td>";


		echo "<select name='water_hold_1'>";
			echo "<option "; if ($water_hold_1 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($water_hold_1 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($water_hold_1 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($water_hold_1 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($water_hold_1 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($water_hold_1 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($water_hold_1 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($water_hold_1 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($water_hold_1 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
		echo "</select>";


		echo "<select name='water_hold_2'>";
			echo "<option "; if ($water_hold_2 == '0') { echo "SELECTED"; } echo " value='0'>0</option>";
			echo "<option "; if ($water_hold_2 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($water_hold_2 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($water_hold_2 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($water_hold_2 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($water_hold_2 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($water_hold_2 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($water_hold_2 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($water_hold_2 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($water_hold_2 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
		echo "</select>";

		echo " &#177; ";

		echo "<select name='water_hold_3'>";
			echo "<option "; if ($water_hold_3 == '1') { echo "SELECTED"; } echo " value='1'>1</option>";
			echo "<option "; if ($water_hold_3 == '2') { echo "SELECTED"; } echo " value='2'>2</option>";
			echo "<option "; if ($water_hold_3 == '3') { echo "SELECTED"; } echo " value='3'>3</option>";
			echo "<option "; if ($water_hold_3 == '4') { echo "SELECTED"; } echo " value='4'>4</option>";
			echo "<option "; if ($water_hold_3 == '5') { echo "SELECTED"; } echo " value='5'>5</option>";
			echo "<option "; if ($water_hold_3 == '6') { echo "SELECTED"; } echo " value='6'>6</option>";
			echo "<option "; if ($water_hold_3 == '7') { echo "SELECTED"; } echo " value='7'>7</option>";
			echo "<option "; if ($water_hold_3 == '8') { echo "SELECTED"; } echo " value='8'>8</option>";
			echo "<option "; if ($water_hold_3 == '9') { echo "SELECTED"; } echo " value='9'>9</option>";
			echo "<option "; if ($water_hold_3 ==  '10') { echo "SELECTED"; } echo " value='10'>10</option>";
		echo "</select>";

		echo " &#176; ";

		echo $units_degrees_format;

		echo "</td></tr>";
		echo "</table><br />";


    
        // water outlets table
        echo "<table width='100%'><tr><td valign='top' width='50%'>";

        ////
        echo "Water Heater Outlets";
        echo "<table id='formtable_heater_outlets'>";
			echo "<tr><td></td></tr>";

			$sql = "SELECT value FROM settings WHERE setting = 'heater_outlets'";
			$heater_outlets = $conn->query($sql);

			foreach ($heater_outlets as $key => $value) {

				$key++;

				echo "<tr valign='top'><td class='counter_heater'>$key </td>

				<td> - <select name='heater_outlets[]'>";

				foreach ($rf_results as $key_a => $value_a) {
					echo "<option ";
						if ($value['value'] == $value_a['id']) { echo 'SELECTED'; } echo " value=".$value_a['id']." >".$value_a['name'];
					echo "</option>";
				}
				echo "</select>";

				echo "
				</td><td><a href='javascript:void(0);' class='remove_heater_outlet button'>Delete</a></td></tr>";

			}

		echo "</table>";

		echo "<table>";
		echo "<tr><td><br /><a class='button new_heater_outlet' href='#'>Add Outlet</a></td></tr>";
		echo "</td></tr></table>";

        
        echo "</td><td valign='top' width='50%'>";

    
        ////
        echo "Water Chiller Outlets";
        echo "<table id='formtable_chiller_outlets'>";
			echo "<tr><td></td></tr>";

			$sql = "SELECT value FROM settings WHERE setting = 'chiller_outlets'";
			$chiller_outlets = $conn->query($sql);

			foreach ($chiller_outlets as $key => $value) {

				$key++;

				echo "<tr valign='top'><td class='counter_chiller'>$key </td>

				<td> - <select name='chiller_outlets[]'>";

				foreach ($rf_results as $key_a => $value_a) {
					echo "<option ";
						if ($value['value'] == $value_a['id']) { echo 'SELECTED'; } echo " value=".$value_a['id']." >".$value_a['name'];
					echo "</option>";
				}
				echo "</select>";

				echo "
				</td><td><a href='javascript:void(0);' class='remove_chiller_outlet button'>Delete</a></td></tr>";

			}

		echo "</table>";

		echo "<table>";
		echo "<tr><td><br /><a class='button new_chiller_outlet' href='#'>Add Outlet</a></td></tr>";
		echo "</td></tr></table>";
        
        // end water outlets table
        echo "</td></tr></table>";



		echo "</div>";
	echo "</div>";



    echo "</div>";


    echo "<br />";


    //////////////////////


    
    echo "<div class='row'>";
    

	echo "<div class='box box-main'>";
		echo "<div class='box-header'>";
			echo "<h5>Timer Controls</h5>";
		echo "</div>";
		echo "<div class='box-body auto-height'>";


		echo "<table id='formtable'>";
			echo "<tr><td></td></tr>";


			foreach ($output as $key => $value) {

				if (strpos($value, 'cron.php') !== false) {

					$key++;

					// get time
					list($before, $after) = explode('/', $value, 2);

					$before = explode('*', $before);
					$before = explode(' ', $before[0]);

					$hour = $before[1];
					$min = $before[0];

					// get attributes
					$after = explode('.php', $after);
					$after = explode('>', $after[1]);
					$after = explode(' ', $after[0]);

					$port 		= $after[1];
					$function 	= $after[2];
					$hour 		= $after[6];
					$min		= $after[7];


					echo "<tr valign='top'><td class='counter'>$key</td>

					<td> - Turn <select class='port' name='port[]'>";

					foreach ($rf_results as $key_a => $value_a) {

						if ($value_a['value_2'] != null) {
							$value_2 = '1';
						} else {
							$value_2 = '';
						}

						echo "<option data-id='$value_2' ";
							if ($port == $value_a['id']) { echo 'SELECTED'; } echo " value='".$value_a['id']."' >".$value_a['name'];
						echo "</option>";
					}
					echo "</select>";


					$function_control = "<option "; if ($function == '0') { $function_control .= " SELECTED"; } $function_control .= " value='0'>Off</option>";
					$function_control .= "<option "; if ($function == '1') { $function_control .= " SELECTED"; } $function_control .= " value='1'>On</option>";

					foreach ($rf_results as $key_a => $value_a) {
						if ($value_a['value_2'] != null && $port == $value_a['id']) {
							$function_control = "<option "; if ($function == '0') { $function_control .= " SELECTED"; } $function_control .= " value='0'>Off</option>";
							$function_control .= "<option "; if ($function == '1') { $function_control .= " SELECTED"; } $function_control .= " value='1'>Low</option>";
							$function_control .= "<option "; if ($function == '2') { $function_control .= " SELECTED"; } $function_control .= " value='2'>Medium</option>";
							$function_control .= "<option "; if ($function == '3') { $function_control .= " SELECTED"; } $function_control .= " value='3'>High</option>";
						}
					}

					echo "</td><td><select class='function' name='function[]'>$function_control</select></td><td> at


					<select name='hour[]'>

					<option "; if ($hour == '0') { echo "SELECTED"; } echo " value='0'>00 (12 AM)</option>
					<option "; if ($hour == '1') { echo "SELECTED"; } echo " value='1'>01 (1 AM)</option>
					<option "; if ($hour == '2') { echo "SELECTED"; } echo " value='2'>02 (2 AM)</option>
					<option "; if ($hour == '3') { echo "SELECTED"; } echo " value='3'>03 (3 AM)</option>
					<option "; if ($hour == '4') { echo "SELECTED"; } echo " value='4'>04 (4 AM)</option>
					<option "; if ($hour == '5') { echo "SELECTED"; } echo " value='5'>05 (5 AM)</option>
					<option "; if ($hour == '6') { echo "SELECTED"; } echo " value='6'>06 (6 AM)</option>
					<option "; if ($hour == '7') { echo "SELECTED"; } echo " value='7'>07 (7 AM)</option>
					<option "; if ($hour == '8') { echo "SELECTED"; } echo " value='8'>08 (8 AM)</option>
					<option "; if ($hour == '9') { echo "SELECTED"; } echo " value='9'>09 (9 AM)</option>
					<option "; if ($hour == '10') { echo "SELECTED"; } echo " value='10'>10 (10 AM)</option>
					<option "; if ($hour == '11') { echo "SELECTED"; } echo " value='11'>11 (11 AM)</option>
					<option "; if ($hour == '12') { echo "SELECTED"; } echo " value='12'>12 (12 PM)</option>
					<option "; if ($hour == '13') { echo "SELECTED"; } echo " value='13'>13 (1 PM)</option>
					<option "; if ($hour == '14') { echo "SELECTED"; } echo " value='14'>14 (2 PM)</option>
					<option "; if ($hour == '15') { echo "SELECTED"; } echo " value='15'>15 (3 PM)</option>
					<option "; if ($hour == '16') { echo "SELECTED"; } echo " value='16'>16 (4 PM)</option>
					<option "; if ($hour == '17') { echo "SELECTED"; } echo " value='17'>17 (5 PM)</option>
					<option "; if ($hour == '18') { echo "SELECTED"; } echo " value='18'>18 (6 PM)</option>
					<option "; if ($hour == '19') { echo "SELECTED"; } echo " value='19'>19 (7 PM)</option>
					<option "; if ($hour == '20') { echo "SELECTED"; } echo " value='20'>20 (8 PM)</option>
					<option "; if ($hour == '21') { echo "SELECTED"; } echo " value='21'>21 (9 PM)</option>
					<option "; if ($hour == '22') { echo "SELECTED"; } echo " value='22'>22 (10 PM)</option>
					<option "; if ($hour == '23') { echo "SELECTED"; } echo " value='23'>23 (11 PM)</option>

					</select><select name='min[]'>

					";

					for ($x = 0; $x <= 59; $x++) {
						$formatted_value = sprintf("%02d", $x);
						echo "<option "; if ($min == $formatted_value) { echo "SELECTED"; } echo " value='$x'>$formatted_value</option>";
					}

					echo "
					</select></td><td><a href='javascript:void(0);' class='remove_wireless_logic button'>Delete</a></td></tr>";

					$min = '';
					$hour = '';
					$port = '';
					$function = '';

				}

			}








		echo "</table><br />";


		echo "<table>";
		echo "<tr><td><a class='button new_wireless_logic' href='#'>New Logic</a></td></tr>";
		echo "</td></tr></table>";


		echo "</div>";
	echo "</div>";




    //////////////////////



    echo "<div class='box box-main'>";
		echo "<div class='box-header'>";
			echo "<h5>Air Temperature Set Point</h5>";
		echo "</div>";
		echo "<div class='box-body auto-height'>";

		echo "<table>";
		echo "<tr><td>Runs every </td><td> <input type='text' name='air_temp_set_point' value='$air_temp_set_point' size='2' minutes if enabled</td></tr>";
		echo "</table>";

		echo "<table id='formtable_air_set_point'>";
			echo "<tr><td></td></tr>";


			$sql = "SELECT value FROM settings WHERE setting = 'air_temp_set_point'";
			$air_set_points = $conn->query($sql);


			foreach ($air_set_points as $key => $value) {

				$air_set = unserialize($value['value']);
				extract($air_set);

				$key++;

				echo "<tr valign='top'><td class='counter_air_temp'>$key</td>

				<td> - Turn <select class='port' name='air_port[]'>";

				foreach ($rf_results as $key_a => $value_a) {

					if ($value_a['value_2'] != null) {
						$value_2 = '1';
					} else {
						$value_2 = '';
					}

					echo "<option data-id='$value_2' ";
						if ($air_port == $value_a['id']) { echo 'SELECTED'; } echo " value='".$value_a['id']."' >".$value_a['name'];
					echo "</option>";
				}
				echo "</select>";



				$function_control = "<option "; if ($air_function == '0') { $function_control .= " SELECTED"; } $function_control .= " value='0'>Off</option>";
				$function_control .= "<option "; if ($air_function == '1') { $function_control .= " SELECTED"; } $function_control .= " value='1'>On</option>";

				foreach ($rf_results as $key_a => $value_a) {
					if ($value_a['value_2'] != null && $air_port == $value_a['id']) {
						$function_control = "<option "; if ($air_function == '0') { $function_control .= " SELECTED"; } $function_control .= " value='0'>Off</option>";
						$function_control .= "<option "; if ($air_function == '1') { $function_control .= " SELECTED"; } $function_control .= " value='1'>Low</option>";
						$function_control .= "<option "; if ($air_function == '2') { $function_control .= " SELECTED"; } $function_control .= " value='2'>Medium</option>";
						$function_control .= "<option "; if ($air_function == '3') { $function_control .= " SELECTED"; } $function_control .= " value='3'>High</option>";
					}
				}

				echo "</td><td><select class='function' name='air_function[]'>$function_control</select></td><td> at

				<select name='air_temp_1[]'>

				<option "; if ($air_temp_1 == '1') { echo "SELECTED"; } echo " value='1'>1</option>
				<option "; if ($air_temp_1 == '2') { echo "SELECTED"; } echo " value='2'>2</option>
				<option "; if ($air_temp_1 == '3') { echo "SELECTED"; } echo " value='3'>3</option>
				<option "; if ($air_temp_1 == '4') { echo "SELECTED"; } echo " value='4'>4</option>
				<option "; if ($air_temp_1 == '5') { echo "SELECTED"; } echo " value='5'>5</option>
				<option "; if ($air_temp_1 == '6') { echo "SELECTED"; } echo " value='6'>6</option>
				<option "; if ($air_temp_1 == '7') { echo "SELECTED"; } echo " value='7'>7</option>
				<option "; if ($air_temp_1 == '8') { echo "SELECTED"; } echo " value='8'>8</option>
				<option "; if ($air_temp_1 == '9') { echo "SELECTED"; } echo " value='9'>9</option>
				<option "; if ($air_temp_1 == '10') { echo "SELECTED"; } echo " value='10'>10</option>


				</select><select name='air_temp_2[]'>

				<option "; if ($air_temp_2 == '0') { echo "SELECTED"; } echo " value='0'>0</option>
				<option "; if ($air_temp_2 == '1') { echo "SELECTED"; } echo " value='1'>1</option>
				<option "; if ($air_temp_2 == '2') { echo "SELECTED"; } echo " value='2'>2</option>
				<option "; if ($air_temp_2 == '3') { echo "SELECTED"; } echo " value='3'>3</option>
				<option "; if ($air_temp_2 == '4') { echo "SELECTED"; } echo " value='4'>4</option>
				<option "; if ($air_temp_2 == '5') { echo "SELECTED"; } echo " value='5'>5</option>
				<option "; if ($air_temp_2 == '6') { echo "SELECTED"; } echo " value='6'>6</option>
				<option "; if ($air_temp_2 == '7') { echo "SELECTED"; } echo " value='7'>7</option>
				<option "; if ($air_temp_2 == '8') { echo "SELECTED"; } echo " value='8'>8</option>
				<option "; if ($air_temp_2 == '9') { echo "SELECTED"; } echo " value='9'>9</option>

				</select> &#177; <select name='air_temp_3[]'>

				<option "; if ($air_temp_3 == '1') { echo "SELECTED"; } echo " value='1'>1</option>
				<option "; if ($air_temp_3 == '2') { echo "SELECTED"; } echo " value='2'>2</option>
				<option "; if ($air_temp_3 == '3') { echo "SELECTED"; } echo " value='3'>3</option>
				<option "; if ($air_temp_3 == '4') { echo "SELECTED"; } echo " value='4'>4</option>
				<option "; if ($air_temp_3 == '5') { echo "SELECTED"; } echo " value='5'>5</option>
				<option "; if ($air_temp_3 == '6') { echo "SELECTED"; } echo " value='6'>6</option>
				<option "; if ($air_temp_3 == '7') { echo "SELECTED"; } echo " value='7'>7</option>
				<option "; if ($air_temp_3 == '8') { echo "SELECTED"; } echo " value='8'>8</option>
				<option "; if ($air_temp_3 == '9') { echo "SELECTED"; } echo " value='9'>9</option>
				<option "; if ($air_temp_3 == '10') { echo "SELECTED"; } echo " value='10'>10</option>

				</select> &#176; $units_degrees_format</td><td><a href='javascript:void(0);' class='remove_air_set_points button'>Delete</a></td></tr>";

			}








		echo "</table><br />";


		echo "<table>";
		echo "<tr><td><a class='button new_air_set_points' href='#'>New Logic</a></td></tr>";
		echo "</td></tr></table>";

		echo "</div>";
	echo "</div>";



	echo "</div>";

    echo "<br /><br />";


    echo "</div>";







		if (isset($save)) {
			$save_text = "Saved";
		} else {
			$save_text = "Save";
		}


	echo "<div class='box box-main' style='width:92%;'>";
		echo "<div class='box-header'>";
			echo "<h5>Save</h5>";
		echo "</div>";
		echo "<div class='box-body'>";

		echo "<tr><td><input type='hidden' name='logic_save'><input class='button save' type='submit' value='$save_text'></td></tr>";


		echo "</div>";
	echo "</div>";

	echo "</form><br />";
