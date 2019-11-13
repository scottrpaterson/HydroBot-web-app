<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Account</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	// save
	if (isset($_POST["account_save"])) {
		
		
		$username 			= filter_login_input($_SESSION['login']);
		
		$email 				= addslashes(filter_login_input($_POST["email"]));
		
		$current_password 	= $_POST["current_password"];
		$current_password_raw = $current_password;
		$current_password 	= md5(filter_login_input($current_password));
		
		$new_password 		= $_POST["new_password"];
		$new_password 		= md5(filter_login_input($new_password));
		
		$new_password_again = $_POST["new_password_again"];
		$new_password_again = md5(filter_login_input($new_password_again));
		
		$name 				= addslashes(filter_login_input($_POST["name"]));
		
		
		// only update email and name fields
		if (empty($current_password_raw)) {
			
			// check to see if current password is correct and get id
			$qry = "SELECT * FROM account WHERE email = '$username'";
			
			$res = $conn->query($qry);
			list($id,$email_old,$password_old,$name_old) = mysqli_fetch_row($res);
			
			$qry = "UPDATE account SET email = '$email', name = '$name' WHERE id = '$id'";
			
			$res = $conn->query($qry);
			
			$_SESSION['login'] = $email;
			
			
			// send variables to api server
			$url 		= 'https://my.hydro.bot/api.php';
			$site_url	= $_SERVER['HTTP_HOST'];
			$myvars 	= 'email='.$email.'&name='.$name.'&key=7FsrVpJsDj27bLyK35myzNpPUVKUrF&url='.$site_url;
			
			$ch = curl_init( $url );
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $myvars);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch,CURLOPT_HEADER, 0);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
			
			$response = curl_exec($ch);
			
			
			$message = 'Successfully updated.';
			
		} else {
			
			// check to see if current password is correct and get id
			$qry = "SELECT * FROM account WHERE email = '$username' and password = '$current_password'";
			
			$res = $conn->query($qry);
			list($id,$email_old,$password_old,$name_old) = mysqli_fetch_row($res);
			
			// check if current password is correct
			if (mysqli_num_rows($res)>0) {
				
				// see if new and new again passwords match
				if ($new_password == $new_password_again) {
					
					// change password
					$qry = "UPDATE account SET password = '$new_password', email = '$email', name = '$name' WHERE id = '$id'";
					
					$res = $conn->query($qry);
					
					$_SESSION['login'] = $email;
					
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
					
					$message = 'Successfully updated.';
					
				} else {
					$message = "New and New Again passwords do not match.";
				}
				
			} else {
				$message = "Current password not correct.";
			}
			
			$save = true;
			
		}
		
	}



	if ($_SESSION['login'] == 'support') {
		echo "The password for this account cannot be changed.<br /><br />";
		echo "Reset password for hydrobot account, <a href='account_default_reset.php'>here</a>.";
		exit;
	}
	
	
	
	// check to see if current password is correct
	
	$username = $_SESSION['login'];
	$qry = "SELECT email,name FROM account WHERE email = '$username'";
	
	$res = $conn->query($qry);
	list($email,$name) = mysqli_fetch_row($res);

	$email 	= stripslashes($email);
	$name 	= stripslashes($name);

	echo "<form autocomplete='off' method='post' action='account.php'>";
	echo "<table><tr>";
	echo "<td>Email Address: </td><td><input type='text' name='email' size='25' value=".htmlspecialchars($email, ENT_QUOTES)."></td>";
	echo "</tr><tr>";
	echo "<td>Name: </td><td><input type='text' name='name' size='25' value='".htmlspecialchars($name, ENT_QUOTES)."'></td>";
	echo "</tr><tr>";
	echo "<td>Current Password: </td><td><input type='password' size='25' name='current_password'></td>";
	echo "</tr><tr>";
	echo "<td>New Password: </td><td><input type='password' size='25' name='new_password'></td>";
	echo "</tr><tr>";
	echo "<td>New Password Again: </td><td><input type='password' size='25' name='new_password_again'></td>";
	echo "</td></tr></table>";
		
		echo "</div>";
	echo "</div>";
	echo "<div class='box'>";
		echo "<div class='box-header'>";
			echo "<h5>Save</h5>";
		echo "</div>";
		echo "<div class='box-body'>";
		
	if (isset($message)) {
		echo "</td><td>".$message."<br /><br /></td><td>";
	}
	
	echo "<input type='submit' class='button' value='Save'>";
	echo "<input type='hidden' name='account_save'>";
	echo "</form>";


	echo "</div>";
echo "</div>";



include_once ('footer.php');

