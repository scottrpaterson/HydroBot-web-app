<?php

include_once ('header.php');




if (isset($_POST["setup_save_settings"])) {
	
	$email = filter_login_input($_POST['email']);
	$name = filter_login_input($_POST['name']);
	
	$sql = "INSERT INTO account (email,name) VALUES ('$email','$name')";
	
	if ($conn->query($sql) === TRUE) {
		
		// send variables to api server
		$url 		= 'https://my.hydro.bot/api.php';
		$site_url	= $_SERVER[HTTP_HOST];
		$myvars 	= 'email='.$email.'&name='.$name.'&key=7FsrVpJsDj27bLyK35myzNpPUVKUrF&url='.$site_url;
		
		$ch = curl_init( $url );
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $myvars);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch,CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($ch);
		
		$saved = 'true';
		
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
}



$sql = "SELECT * FROM account";
$results = $conn->query($sql);
list($id,$email,$name) = mysqli_fetch_row($results);
	
echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>my.hydro.bot Setup</h5>";
	echo "</div>";
	echo "<div class='box-body'>";
		echo "<form name='setup' method='post' action='index.php'>";
			echo "<table><tr>";
			echo "<td>Email Address:</td>";
			echo "<td><input name='email' type='text' maxlength='50' size='40' required value='$email'></td><td>(Your email is used to login to my.hydro.bot)";
			echo "</tr><tr>";
			echo "<td>HydroBot Name:</td>";
			echo "<td><input name='name' type='text' maxlength='50' size='40' required value='$name'></td><td>(Give this Hydrobot a unique name, like Scott's HydroBot)</td>";
			echo "</tr><tr>";
			echo "<td><input type='submit' name='login' value='Save' class='button'></td>";
			echo "</tr></table>";
			echo "<input type='hidden' name='setup_save_settings'>";
		echo "</form>";
	echo "</div>";
echo "</div>";



include_once ('footer.php');
