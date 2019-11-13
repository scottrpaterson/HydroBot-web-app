<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Administration Tools</h5>";
	echo "</div>";
	echo "<div class='box-body'>";
	
	$local_ip  	= exec("hostname -I 2>&1");
	
	echo "<table>";
	
	echo "<tr><td>Local IP: </td><td><a class='button' href='http://". $local_ip ."'>". $local_ip ."</a></td></tr>";
	echo "<tr><td>Database: </td><td><a class='button' href='http://". $local_ip ."/phpmyadmin'>". $local_ip ."/phpmyadmin</a> - (Database can only be accessed from a local network for security.)</td></tr>";
	echo "<tr><td>Software Update: </td><td><a class='button' href='update.php'>Check for updates</a></td></tr>";
	echo "<tr><td>Wifi Reset: </td><td><a class='button' href='reset_wifi.php'>Reset</a></td></tr>";
	echo "<tr><td>Factory Reset: </td><td><a class='button' href='reset.php'>Reset</a></td></tr>";
	echo "</table>";

	echo "</div>";
echo "</div>";


include_once ('footer.php');