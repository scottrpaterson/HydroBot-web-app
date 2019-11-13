<?php

include_once ('header.php');




echo "<div class='box'>";
	echo "<div class='box-header'>";
		echo "<h5>Reports</h5>";
	echo "</div>";
	echo "<div class='box-body'>";


?>

<script type="text/javascript" src="https://hydro.bot/files/assets/jquery.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jquery-ui.min.js"></script>


<script type="text/javascript" src='https://hydro.bot/files/assets/datepicker.js'></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/timepicker-addon.js"></script>
<script type="text/javascript" src='https://hydro.bot/files/assets/reports.js'></script>


<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.logAxisRenderer.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.highlighter.js"></script>



<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
<script type="text/javascript" src="https://hydro.bot/files/assets/jqplot/plugins/jqplot.cursor.js"></script>

<link rel="stylesheet" media="all" type="text/css" href="https://hydro.bot/files/assets/jquery-ui.css">
<link rel="stylesheet" media="all" type="text/css" href="https://hydro.bot/files/assets/timepicker-addon.css">
<link rel="stylesheet" media="all" type="text/css" href="https://hydro.bot/files/assets/jqplot/jquery.jqplot.css" />
<link rel="stylesheet" media="all" type="text/css" href="https://hydro.bot/files/assets/style.css">


<?php



date_default_timezone_set($timezone_offset);

// get values
if (isset($_POST['from'])) {
	$from = 		$_POST['from'];
	$fromhidden = 	$_POST['fromhidden'];
} else {
	$from = 		date( 'n/j/Y g:i A', time() - 604800); // 7 days
	$fromhidden = 	date( 'Y-m-d H:i:s', time() - 604800);
}

if (isset($_POST['to'])) {
	$to = 		$_POST['to'];
	$tohidden = $_POST['tohidden'];
} else {
	$to = 		date( 'n/j/Y g:i A');
	$tohidden = date( 'Y-m-d H:i:s');
}



if (isset($_POST['list'])) {
	$list = $_POST['list'];
} else {
	$list = '1';
}






// get data from db
$sql 				= "SELECT date, air_temp, air_hum, water_temp, water_ph, water_ec FROM log WHERE date BETWEEN '$fromhidden' AND '$tohidden';";
$report_results 	= $conn->query($sql);

// count the number of results
$num_report_results = $report_results->num_rows;


echo "<form method='POST'>";
echo "<table>";
echo "<tr><td>From: </td><td><input type='text' id='fromdate' name='from' size='25' value='$from'><input type='hidden' id='fromdatehidden' name='fromhidden' value='$fromhidden'></td>";
echo "<td>To: </td><td><input type='text' id='todate' name='to' size='25' value='$to'><input type='hidden' id='todatehidden' name='tohidden' value='$tohidden'></td>";
echo "<td><input type='submit' value='Run Report' class='button'></td></tr>";
echo "</table>";
echo "</form>";
echo "<br /><br />";


if ($num_report_results > 0) {
    
    echo "Data points: $num_report_results";
    echo "<br />";
    echo "<input type='button' value='Export' class='button' id='export-csv-reports'>";

	echo "<div id='logplot_air' class='jqplot-target' style='width: 100%; height: 500px; max-width: 1000px;'></div>";
    echo "<br /><br />";
    echo "<div id='logplot_water' class='jqplot-target' style='width: 100%; height: 500px; max-width: 1000px;'></div>";
    echo "<br /><br />";
?>









<script>

	$(document).ready(function() {

		var dataSets = {

			airtemp:
			[
			<?php
			foreach ($report_results as $key => $value) {
                if ($units_degrees_format == 'F') { 
                    $air_temp = ($value['air_temp'] * 9/5) + 32;
                } else {
                    $air_temp = $value['air_temp'];
                }
                if (!empty($value['air_temp'])) {
				    echo "['".date('Y-m-d h:iA', strtotime($value['date']))."',".$air_temp."],";
                }
			}
			?>
			],

			airhum:
			[
			<?php
			foreach ($report_results as $key => $value) {
				echo "['".date('Y-m-d h:iA', strtotime($value['date']))."',".$value['air_hum']."],";
			}
			?>
			],

			watertemp:
			[
			<?php
			foreach ($report_results as $key => $value) {
                if ($units_degrees_format == 'F') { 
                    $water_temp = ($value['water_temp'] * 9/5) + 32;
                } else {
                    $water_temp = $value['water_temp'];
                }
                if (!empty($value['water_temp'])) {
				    echo "['".date('Y-m-d h:iA', strtotime($value['date']))."',".$water_temp."],";
                }
			}
			?>
			],

			waterph:
			[
			<?php
			foreach ($report_results as $key => $value) {
				echo "['".date('Y-m-d h:iA', strtotime($value['date']))."',".$value['water_ph']."],";
			}
			?>
			],

			waterec:
			[
			<?php
			foreach ($report_results as $key => $value) {
				echo "['".date('Y-m-d h:iA', strtotime($value['date']))."',".$value['water_ec']."],";
			}
			?>
			],

		}



        // air temp / hum
		$.jqplot.config.enablePlugins = true;

		var plot1 = $.jqplot('logplot_air', [dataSets.airtemp, dataSets.airhum], {
                
            title: 'Air Temperature / Humidity',
            
			highlighter: {
				show: true,
				sizeAdjust: 20,
				showTooltip: true
			},

			legend: {
				show: false,
			},

			cursor: {
				show: false,
				tooltipLocation:'sw'
			},

			seriesDefaults: {
				fill: false,
				rendererOptions: {
					smooth: true,
					animation: {
						show: false
					},
				},
				showMarker: true
			},
            
            series : [{
                yaxis : 'yaxis',
                color: 'darkblue', highlightColors: [],
            }, {
                yaxis : 'y2axis',
                color: 'green', highlightColors: []
            }],
            
            tickOptions: {
                // labelPosition: 'middle',
                angle: 15,
                showGridline: false,
                textColor: '#ffffff'
            },

			axes:{
				xaxis:{
					renderer:$.jqplot.DateAxisRenderer,
                    label: 'Time',
				},
				yaxis: {
					autoscale:true,
					min: 0,
					tickOptions: {
						formatString: '%.2f',
                        textColor: 'darkblue',
					},
                    label: 'Temperature ( º<?php if ($units_degrees_format == 'F') { echo 'F'; } else { echo 'C'; } ?> )',
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    labelOptions: {textColor: 'darkblue'},
				},
                y2axis: {
					autoscale:true,
					min: 0,
					tickOptions: {
						formatString: '%.2f',
                        textColor: 'green',
					},
                    label: 'Humidity ( % )',
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    labelOptions: {textColor: 'green'},
				},
			},

		});
        
        
        
        
        
        
        
        
        // water ph, ec, temp
		$.jqplot.config.enablePlugins = true;

		var plot1 = $.jqplot('logplot_water', [dataSets.waterph, dataSets.waterec, dataSets.watertemp], {
                
            title: 'Water PH / EC / Temperature',
            
			highlighter: {
				show: true,
				sizeAdjust: 20,
				showTooltip: true
			},

			legend: {
				show: false,
			},

			cursor: {
				show: false,
				tooltipLocation:'sw'
			},

			seriesDefaults: {
				fill: false,
				rendererOptions: {
					smooth: true,
					animation: {
						show: false
					},
				},
				showMarker: true
			},
            
            series : [
                {
                yaxis : 'yaxis',
                color: 'darkblue', highlightColors: [],
                }, {
                yaxis : 'y2axis',
                color: 'green', highlightColors: []
                }, {
                yaxis : 'y3axis',
                color: 'red', highlightColors: []
                }
                    ],
            
            tickOptions: {
                // labelPosition: 'middle',
                angle: 15,
                showGridline: false,
                textColor: '#ffffff'
            },

			axes:{
				xaxis:{
					renderer:$.jqplot.DateAxisRenderer,
                    label: 'Time',
				},
				yaxis: {
					autoscale:true,
					min: 0,
					tickOptions: {
						formatString: '%.2f',
                        textColor: 'darkblue',
					},
                    label: 'PH',
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    labelOptions: {textColor: 'darkblue'},
				},
                y2axis: {
					autoscale:true,
					min: 0,
					tickOptions: {
						formatString: '%.2f',
                        textColor: 'green',
					},
                    label: 'EC',
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    labelOptions: {textColor: 'green'},
				},
                y3axis: {
					autoscale:true,
					min: 0,
					tickOptions: {
						formatString: '%.2f',
                        textColor: 'red',
					},
                    label: 'Temperature ( º<?php if ($units_degrees_format == 'F') { echo 'F'; } else { echo 'C'; } ?> )',
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    labelOptions: {textColor: 'red'},
				},
			},

		});
        
        
        
        
        
        
        
        
        


	});


</script>

<br />



<?php

}

echo "</div>";
echo "</div>";



include_once ('footer.php');