<?php

include_once ('header.php');




echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Outlets</h5>";
	echo "</div>";
	echo "<div class='box-body'>";

	$sql = "SELECT * FROM rf ORDER BY name ASC";
	$results = $conn->query($sql);


	echo "<table>";

	foreach ($results as $result) {
		echo "<tr><td></td></tr>";
		
		if ($result['value_2'] != null) {
			echo "<tr><td>".$result['name'].": </td>
			<td><a class='button action ".$result['id']."' id='".$result['id']."_1' data-id='".$result['id']."' data-value='".$result['value_1']."' data-length='".$result['length_1']."' data-delay='".$result['delay_1']."' href='#'>Low</a></td>
			<td><a class='button action ".$result['id']."' data-id='".$result['id']."' id='".$result['id']."_2' data-value='".$result['value_2']."' data-length='".$result['length_2']."' data-delay='".$result['delay_2']."' href='#'>Medium</a></td>
			<td><a class='button action ".$result['id']."' data-id='".$result['id']."' id='".$result['id']."_3' data-value='".$result['value_3']."' data-length='".$result['length_3']."' data-delay='".$result['delay_3']."' href='#'>High</a></td>
			<td><a class='button action ".$result['id']."' data-id='".$result['id']."' id='".$result['id']."_0' data-value='".$result['value_0']."' data-length='".$result['length_0']."' data-delay='".$result['delay_0']."' href='#'>Off</a>
			</td></tr>";
		} else {
			echo "<tr>
			<td>".$result['name'].": </td>
			<td><a class='button action ".$result['id']."' id='".$result['id']."_1' data-id='".$result['id']."' data-value='".$result['value_1']."' data-length='".$result['length_1']."' data-delay='".$result['delay_1']."' href='#'>On</a></td>
			<td><a class='button action ".$result['id']."' data-id='".$result['id']."' id='".$result['id']."_0' data-value='".$result['value_0']."' data-length='".$result['length_0']."' data-delay='".$result['delay_0']."' href='#'>Off</a></td>
			</tr>";
		}
	}
	
	if (mysqli_num_rows($results)==0) {
		echo "No outlets have been added. <a href='/portal/settings.php' class='button'>Add a new RF outlet</a>";
	}


	echo "</table>";


	echo "</div>";
echo "</div>";


//////////



echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Peristaltic Pumps</h5>";
	echo "</div>";
	echo "<div class='box-body'>";


	echo "<table>";

	echo "<tr><td>";

	echo "<select id='manual_ph'>";
		echo "<option value='up'>pH Up</option>";
		echo "<option value='down'>pH Down</option>";
	echo "</select>";

	echo "</td></tr><tr><td>";

	echo "<select id='manual_ph_amount'>";
		for ($x = 0; $x <= 100; $x++) {
			echo "<option value='$x'>$x</option>";
		}
	echo "</select> mL";

	echo "</td></tr><tr><td>";

	echo "<input type='submit' class='button' id='ph_run' value='Go'>";

	echo "</td></tr></table>";
	
	echo "</div>";
echo "</div>";



include_once ('footer.php');