<?php

// send commands to pumps

// this file needs to convert ml to seconds

if (isset($_POST["function"])) {

	$function 	= $_POST["function"];
	$amount 	= $_POST["amount"];
	
	// convert seconds to ml
	$amount = $amount * 1.275 * 1000;
	
	exec("python2 /var/www/html/portal/includes/ph_controller.py 'input_motor_ph_$function' '$amount' '255' 2>&1");
	
	$response = array(
		'result'      => true,
	);

	echo json_encode($response);

	exit();
	
}