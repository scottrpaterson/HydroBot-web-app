<?php

include_once ('header.php');


// save
if (isset($_POST["account_save_reset"])) {
	
	// change password to 'hydrobot'
	$qry = "UPDATE users SET password = 'd978651bcad6b63bd0438686c6ddce2a' WHERE username = 'hydrobot'";
	
	$res = $conn->query($qry);
	
	$message = 'Password for account hydrobot successfully reset password to: hydrobot.';
	
	$save = true;
	
	
}



echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Account</h5>";
	echo "</div>";
	echo "<div class='box-body'>";



	echo "Are you sure you want to reset the password for hydrobot account: <br /><br />";


	echo "<form autocomplete='off' method='post' action='account_default_reset.php'>";
	echo "<table><tr><td>";
	echo "<input type='submit' class='button' value='Yes'> &nbsp; ";
	echo "<a href='/portal/settings.php' class='button'>Cancel</a>";
	echo "<input type='hidden' name='account_save_reset'>";
	echo "</td><td>".$message."</td><td>";
	echo "</td></tr></table>";
	echo "</form>";


	echo "</div>";
echo "</div>";



include_once ('footer.php');
