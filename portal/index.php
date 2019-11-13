<?php

include_once ('header.php');

echo "<script src='https://hydro.bot/files/assets/guage.js'></script>";


echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Stats</h5>";
	echo "</div>";
	echo "<div class='box-body'>";





    // check to see if data logging is enabled

	$sql = "SELECT value FROM settings WHERE setting = 'automation_settings'";
	$automation_settings_results = $conn->query($sql);


	if (mysqli_num_rows($automation_settings_results)>0) {
		list($automation_settings) = mysqli_fetch_row($automation_settings_results);
		
		$automation_settings = unserialize($automation_settings);
		extract($automation_settings);
	} else {
		$log = '';
	}


    if ($log == '1') {
        
        $sql_log = "SELECT * FROM log ORDER BY id DESC LIMIT 1";
        $log_results = $conn->query($sql_log);
		
		if (mysqli_num_rows($log_results)==0) {
			
			$air_temp 	= '';
			$air_hum 	= '';
			$water_temp = '';
			$water_ph 	= '';
			$water_ec 	= '';
			$uptime 	= '';
			
		} else {
			
			list($id,$date,$air_temp,$air_hum,$water_temp,$water_ph,$water_ec,$uptime) = mysqli_fetch_row($log_results);
			
		}
		
    } else {
		
		$air_temp 	= '';
		$air_hum 	= '';
		$water_temp = '';
		$water_ph 	= '';
		$water_ec 	= '';
		$uptime 	= '';
		$date 		= '';
		
	   echo "<center><input type='button' id='get_stats' class='button' value='Get Current Reading'></center>";
        
    }


    $minutes =  $uptime;
    $d = floor ($minutes / 1440);
    $h = floor (($minutes - $d * 1440) / 60);
    $m = $minutes - ($d * 1440) - ($h * 60);

    if ($uptime != '') { $uptime =  "$d days, $h hours, $m minutes"; }      else { $uptime = '-'; }
    if ($date != '')   { $date =  date('Y-m-d H:i:s A', strtotime($date)); } else { $date = '-'; }



	echo "<div style=''>";
		echo "System Uptime: <div id='uptime'>".$uptime."</div>";
		echo "Readings Taken: <div id='date'>".$date."</div>";
	echo "</div>";

echo "<div class='guage-container'>";

// ph gauge
	echo "<div class='guage-box'>
	<canvas id='ph-guage' data-type='radial-gauge'
	    data-width='300'
	    data-height='300'
	    data-units='pH'
	    data-min-value='0'
			data-max-value='14'
	    data-major-ticks='0,2,4,6,8,10,12,14'
	    data-minor-ticks='2'
	    data-stroke-ticks='true'
	    data-highlights='[
	        {'from': 8, 'to': 14, 'color': 'rgba(200, 50, 50, .75)'}
	    ]'
	    data-color-plate='#fff'
	    data-border-shadow-width='0'
	    data-borders='false'
	    data-needle-type='arrow'
	    data-needle-width='3'
	    data-needle-circle-size='7'
	    data-needle-circle-outer='true'
	    data-needle-circle-inner='true'
	    data-animation-duration='500'
	    data-animation-rule='linear'
			data-value-box='true'
			data-value='$water_ph'
	></canvas></div>
";


// EC gauge
	echo "<div class='guage-box'>";
	echo "<canvas id='ec-guage' data-type='radial-gauge'
		    data-width='300'
		    data-height='300'
		    data-units='EC (ppm)'
		    data-min-value='0'
				data-max-value='4000'
		    data-major-ticks='0,500,1000,1500,2000,2500,3000,3500,4000'
		    data-minor-ticks='2'
		    data-stroke-ticks='true'
		    data-highlights='[
		        {'from': 8, 'to': 14, 'color': 'rgba(200, 50, 50, .75)'}
		    ]'
		    data-color-plate='#fff'
		    data-border-shadow-width='0'
		    data-borders='false'
		    data-needle-type='arrow'
		    data-needle-width='3'
		    data-needle-circle-size='7'
		    data-needle-circle-outer='true'
		    data-needle-circle-inner='true'
		    data-animation-duration='500'
		    data-animation-rule='linear'
				data-value-box='true'
				data-value='$water_ec'
		></canvas></div>
	";


	// Relative Humidity gauge
		echo "<div class='guage-box'>";
		echo "<canvas id='rh-guage' data-type='radial-gauge'
		    data-width='300'
		    data-height='300'
		    data-units='Humidity %'
		    data-min-value='0'
				data-max-value='100'
		    data-major-ticks='0,10,20,30,40,50,60,70,80,90,100'
		    data-minor-ticks='2'
		    data-stroke-ticks='true'
		    data-highlights='[
		        {'from': 8, 'to': 14, 'color': 'rgba(200, 50, 50, .75)'}
		    ]'
		    data-color-plate='#fff'
		    data-border-shadow-width='0'
		    data-borders='false'
		    data-needle-type='arrow'
		    data-needle-width='3'
		    data-needle-circle-size='7'
		    data-needle-circle-outer='true'
		    data-needle-circle-inner='true'
		    data-animation-duration='500'
		    data-animation-rule='linear'
				data-value-box='true'
				data-value='$air_hum'
		></canvas></div>
	";


	echo "<br />";


	if ($units_degrees_format == 'F') {
        
        
        function convert_to_f($c) {
            return $f = ($c * 9/5) + 32;
        }
        
        $water_temp = convert_to_f($water_temp);
        $air_temp   = convert_to_f($air_temp);

        // water temp
        echo "<div class='guage-box-temp'>";
        echo "<canvas id='water-guage' data-type='linear-gauge'
            data-width='150'
            data-height='400'
            data-units='Water Temperature (Fº)'
            data-min-value='0'
            data-max-value='120'
            data-major-ticks='0,10,20,30,40,50,60,70,80,90,100,110,120'
            data-minor-ticks='2'
            data-stroke-ticks='true'
            data-highlights='[ {'from': 100, 'to': 220, 'color': 'rgba(200, 50, 50, .75)'} ]'
            data-color-plate='#fff'
            data-border-shadow-width='0'
            data-borders='false'
            data-needle-type='arrow'
            data-needle-width='5'
            data-animation-duration='500'
            data-animation-rule='linear'
            data-tick-side='right'
            data-number-side='right'
            data-needle-side='left'
            data-bar-stroke-width='2'
            data-bar-begin-circle='false'
            data-value='$water_temp'
        ></canvas></div>
        ";


        // air temp
        echo "<div class='guage-box-temp'>";
        echo "<canvas id='air-guage' data-type='linear-gauge'
            data-width='150'
            data-height='400'
            data-units='Air Temperature (Fº)'
            data-min-value='0'
            data-max-value='120'
            data-major-ticks='0,10,20,30,40,50,60,70,80,90,100,110,120'
            data-minor-ticks='2'
            data-stroke-ticks='true'
            data-highlights='[ {'from': 100, 'to': 220, 'color': 'rgba(200, 50, 50, .75)'} ]'
            data-color-plate='#fff'
            data-border-shadow-width='0'
            data-borders='false'
            data-needle-type='arrow'
            data-needle-width='5'
            data-animation-duration='500'
            data-animation-rule='linear'
            data-tick-side='right'
            data-number-side='right'
            data-needle-side='left'
            data-bar-stroke-width='2'
            data-bar-begin-circle='false'
            data-value='$air_temp'
        ></canvas></div>
        ";

    } else {

        // water temp
        echo "<div class='guage-box-temp'>";
        echo "<canvas id='water-guage' data-type='linear-gauge'
            data-width='150'
            data-height='400'
            data-units='Water Temperature (Cº)'
            data-min-value='-10'
            data-max-value='50'
            data-major-ticks='-10,0,10,20,30,40,50'
            data-minor-ticks='2'
            data-stroke-ticks='true'
            data-highlights='[ {'from': 30, 'to': 50, 'color': 'rgba(200, 50, 50, 1)'} ]'
            data-color-plate='#fff'
            data-border-shadow-width='0'
            data-borders='false'
            data-needle-type='arrow'
            data-needle-width='5'
            data-animation-duration='500'
            data-animation-rule='linear'
            data-tick-side='right'
            data-number-side='right'
            data-needle-side='left'
            data-bar-stroke-width='2'
            data-bar-begin-circle='false'
            data-value='$water_temp'
        ></canvas></div>
        ";


        // air temp
        echo "<div class='guage-box-temp'>";
        echo "<canvas id='air-guage' data-type='linear-gauge'
            data-width='150'
            data-height='400'
            data-units='Air Temperature (Cº)'
            data-min-value='-10'
            data-max-value='50'
            data-major-ticks='-10,0,10,20,30,40,50'
            data-minor-ticks='2'
            data-stroke-ticks='true'
            data-highlights='[ {'from': 100, 'to': 220, 'color': 'rgba(200, 50, 50, .75)'} ]'
            data-color-plate='#fff'
            data-border-shadow-width='0'
            data-borders='false'
            data-needle-type='arrow'
            data-needle-width='5'
            data-animation-duration='500'
            data-animation-rule='linear'
            data-tick-side='right'
            data-number-side='right'
            data-needle-side='left'
            data-bar-stroke-width='2'
            data-bar-begin-circle='false'
            data-value='$air_temp'
        ></canvas></div>
        ";

    }

echo "</div>";

	echo "</div>";
echo "</div>";


include_once ('footer.php');
