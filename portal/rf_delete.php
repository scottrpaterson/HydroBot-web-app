<?php

include_once ('header.php');


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Delete an outlet</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

$sql = "SELECT id,name FROM rf ORDER BY name ASC";
$results = $conn->query($sql);


echo "<table>";

foreach ($results as $result) {	
	echo "<tr><td><br /></td></tr>";
	echo "<tr><td>".$result['name'].": </td><td><a class='button delete' id='".$result['id']."' href=''>Delete </a></td></tr>";
}

	echo "</div>";
echo "</div>";

include_once ('footer.php');