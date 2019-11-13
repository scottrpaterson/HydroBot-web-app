<?php
	session_start();

	include_once('/var/www/html/db.php');


	// current version
	$version 		= "1.1";
	$download_url 	= "https://hydro.bot/files/hydrobot.zip";





	if (isset($_GET["logout"])) {
		session_destroy();
		header("location: index.php");
		exit();
	}

	if (isset($_POST["login"])) {

		$email 		= $password = "";
		$email 		= $_POST["email"];
		$email 		= filter_login_input($email);
		$password 	= $_POST["password"];
		$password 	= md5(filter_login_input($password));

		$qry = "SELECT * FROM account WHERE email='$email' and password='$password'";

		$res = $conn->query($qry);

		if (mysqli_num_rows($res)>0) {
			$_SESSION['login'] = $email;
		} else {
			$loginCheck = "0";
		}
	}
	function filter_login_input($loginData) {
		$loginData = trim($loginData);
		$loginData = stripslashes($loginData);
		$loginData = htmlspecialchars($loginData);
		return $loginData;
	}
?>

<!doctype html>
<html itemscope="" itemtype="http://schema.org/WebPage" lang="en">
<head>

<title>HydroBot</title>
<link rel="stylesheet" type="text/css" href="https://hydro.bot/files/assets/style.css" media="all">


<script type="text/javascript">
	<?php
	// pass value to external javascript file
	$sql = "SELECT id,name,value_2 FROM rf ORDER BY name ASC";
	$rf_results = $conn->query($sql);


	echo "var outlets = [";

	foreach ($rf_results as $key => $value) {

		if ($value['value_2'] != null) {
			echo '"'.$value['name'].'|'.$value['id'].'|1",';
		} else {
			echo '"'.$value['name'].'|'.$value['id'].'",';
		}
	}
	?>
	];


	<?php
	// get units degrees format
	$sql = "SELECT value FROM settings WHERE setting = 'units_degrees_format'";
	$settings_results = $conn->query($sql);

	list($units_degrees_format) = mysqli_fetch_row($settings_results);

	$units_degrees_format = ucfirst($units_degrees_format);

	echo "var degrees_format = ['$units_degrees_format']";
    
    // get timezone offset
	$sql = "SELECT value2 FROM settings WHERE setting = 'timezone'";
	$settings_results = $conn->query($sql);

	list($timezone_offset) = mysqli_fetch_row($settings_results);
	
	
	
	// make a timezone list
	function get_timezones() {
		$o = array();
		
		$t_zones = timezone_identifiers_list();
		
		foreach($t_zones as $a) {
			$t = '';
			
			//this throws exception for 'US/Pacific-New'
			$zone = new DateTimeZone($a);
			
			$seconds = $zone->getOffset( new DateTime("now" , $zone) );
			$hours = sprintf( "%+02d" , intval($seconds/3600));
			$minutes = sprintf( "%02d" , ($seconds%3600)/60 );
			
			$offset = "$hours:$minutes";
			
			$o[$a] = $offset;
		}
		
		ksort($o);
		
		return $o;
	}
    
	?>


</script>




<script src='https://hydro.bot/files/assets/jquery.js'></script>
<script src='https://hydro.bot/files/assets/scripts.js'></script>

<!-- refresh page every 20 minutes to check for active login session -->
<meta http-equiv="refresh" content="1200" />

<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

<div class='body'>






<header class="header">
  <a href='/portal/'><img style='width: 250px;' src='https://hydro.bot/files/assets/hydro.bot_logo.png'></a>
	<?php
	// header menu
	if (isset($_SESSION['login'])) {
		?>
		<input class="menu-btn" type="checkbox" id="menu-btn" />
		<label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
		<ul class="menu">
			<li><a href='/portal/'>Home</a></li>
			<li><a href='/portal/manual.php'>Manual</a></li>
			<li><a href='/portal/logic.php'>Automation</a></li>
			<li><a href='/portal/reports.php'>Reports</a></li>
			<li><a href='/portal/settings.php'>Settings</a></li>
			<li><a href='/portal/index.php?logout=true'>Log Out</a></li>
		</ul>
		<?php
		}
	?>
</header>









<table><tr>
<td></td>
</tr></table>


<br /><br /><br /><br />



<div class='row'>




<?php
// check to see if wifi config has been run
$filename = '/etc/cron.raspiwifi/aphost_bootstrapper';

if (file_exists($filename)) {

	echo "Setup has not run yet, redirecting to port 90";

	header("Location: http://10.0.0.1:90");
	die();

}




// save hydrobot setup

if (isset($_POST["setup_save"])) {

	$email 				= addslashes(filter_login_input($_POST['email']));
	$name 				= addslashes(filter_login_input($_POST['name']));

	$new_password 		= $_POST["new_password"];
	$new_password 		= md5(filter_login_input($new_password));

	$new_password_again = $_POST["new_password_again"];
	$new_password_again = md5(filter_login_input($new_password_again));
	
	$timezone = filter_login_input($_POST['timezone']);
	$timezone = explode("|",$timezone);

	if ($new_password == $new_password_again) {
		
		// timezone
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
		
		// units
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
		
		// account
		$sql = "INSERT INTO account (email,password,name) VALUES ('$email','$new_password','$name')";
		
		if ($conn->query($sql) === TRUE) {
		
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
		
		echo "<div class='box'>";
			echo "<div class='box-header'>";
				echo "<h5>Initial Setup</h5>";
			echo "</div>";
			echo "<div class='box-body'>";
				echo "Setup Successful";
				echo "<br /><br />";
				echo "<a href='/' class='button'>Continue</a>";
			echo "</div>";
		echo "</div>";
		exit;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
	} else {
		$message = "Passwords do not match.";
	}

}



// check to see if hydrobot seutp has been run

$qry = "SELECT * FROM account";

$res = $conn->query($qry);

if (mysqli_num_rows($res)==0) {

	echo "<div class='box'>";
		echo "<div class='box-header'>";
			echo "<h5>Initial Setup</h5>";
		echo "</div>";
		echo "<div class='box-body'>";
			echo "<form name='setup' method='post' action='index.php'>";
				echo "<table><tr>";
				
				echo "<td>Email Address:</td>";
				echo "<td><input name='email' type='text' size='25' maxlength='50' required></td><td>";
				echo "</tr><tr>";
				
				echo "<td>Password: </td><td><input type='password' size='25' name='new_password'></td>";
				echo "</tr><tr>";
				
				echo "<td>Password Again: </td><td><input type='password' size='25' name='new_password_again'></td>";
				echo "</tr><tr>";
				
				echo "<td>HydroBot Name:</td>";
				echo "<td><input name='name' type='text' maxlength='50' size='25'  required> (Give this Hydrobot a unique name, like Scott's HydroBot)</td>";
				echo "</tr><tr>";
				
				echo "<td>Timezone:</td>";
				echo "<td><select name='timezone'>";
					$o = get_timezones();
					
					
					foreach($o as $tz => $label)
					{
						echo "<option value='$label|$tz'>$tz [$label]</option>";
					}
				echo "</select></td>";
				echo "</tr><tr>";
				
				echo "<td>Units:</td>";
				echo "<td><select name='units_degrees_format'>";
					echo "<option "; if ($units_degrees_format == 'F') { echo "SELECTED"; } echo " value='F'>Fahrenheit</option>";
					echo "<option "; if ($units_degrees_format == 'C') { echo "SELECTED"; } echo " value='C'>Celsius</option>";
				echo "</select></td>";
				
				
				echo "</tr><tr>";
				if (isset($message)) {
					echo "<td colspan='2' class='error'><br />".$message."</td>";
				}
				echo "</tr><tr>";
				echo "<td><br /><input type='submit' value='Save' class='button'></td>";
				echo "</tr></table>";
				echo "<input type='hidden' name='setup_save'>";
			echo "</form>";
		echo "</div>";
	echo "</div>";

	exit;

}





// show login form if user is not logged in
if (!isset($_SESSION['login'])) {
?>
	<div class='box'>
		<div class='box-header'>
			<h5>LogIn</h5>
		</div>
		<div class='box-body'>
			<form name="loginForm" method="post">
				<?php
					$qry = "SELECT name FROM account";

					$res = $conn->query($qry);
					list($name) = mysqli_fetch_row($res);
				?>
				<td><h3><center><?php echo $name; ?></center></h3<</td>
				<table><tr>
				<td>Email Address:</td>
				<td><input name="email" type="text" maxlength="40" size="25" required value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } elseif(isset($_GET['email'])) { echo $_GET['email']; } ?>"></td>
				</tr><tr>
				<td>Password:</td>
				<td><input name="password" type="password" maxlength="40" size="25" required></td>
				</tr><tr>
				<td colspan='2'>
				<?php
					if(isset($loginCheck))
					{
						echo "<label class='error'>Invalid login</label><br/>";
					}
				?>
				</td>
				</tr><tr>
				<td><input type='hidden' name='login'><input type="submit" name="login" value="Log In" class='button'></td>
				</tr></table>
			</form>
		</div>
	</div>
	<?php
	exit;
}
