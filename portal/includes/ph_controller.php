<?php

// send commands to pumps

if (isset($_POST["function"])) {
	$function 	= $_POST["function"];
	$length 	= $_POST["length"];
	
	
	exec("python2 /var/www/html/portal/includes/ph_controller.py 'input_motor_ph_$function' '$length' '255' 2>&1");
	
	
	$response = array(
		'result'      => true,
	);

	echo json_encode($response);

	exit();
	
}