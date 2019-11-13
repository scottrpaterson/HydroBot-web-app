<?php

$from_date = $_GET['from_date'];
$to_date   = $_GET['to_date'];

include_once('/var/www/html/db.php');


// get units degrees format
$sql = "SELECT value FROM settings WHERE setting = 'units_degrees_format'";
$settings_results = $conn->query($sql);

list($units_degrees_format) = mysqli_fetch_row($settings_results);

$units_degrees_format = ucfirst($units_degrees_format);


//get records from database
$sql 				= "SELECT date, air_temp, air_hum, water_temp, water_ph, water_ec FROM log WHERE date BETWEEN '$from_date' AND '$to_date';";


$report_results 	= $conn->query($sql);


//if($report_results->num_rows > 0) {
    $delimiter = ",";
    $filename = "reports_export_" . date('m-d-Y') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers

    if ($units_degrees_format == 'F') {
        $fields = array('Date', 'Air Temperature (F)', 'Air Humidity', 'Water Temperature (F)', 'Water pH', 'Water EC');
    } else {
        $fields = array('Date', 'Air Temperature (C)', 'Air Humidity', 'Water Temperature (C)', 'Water pH', 'Water EC');  
    }

    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    foreach ($report_results as $key => $value) {
        
        if ($units_degrees_format == 'F') {
            $lineData = array($value['date'], round_value(convert_to_f($value['air_temp'])), $value['air_hum'], round_value(convert_to_f($value['water_temp'])), $value['water_ph'], $value['water_ec'], $status);
        } else {
            $lineData = array($value['date'], $value['air_temp'], $value['air_hum'], $value['water_temp'], $value['water_ph'], $value['water_ec'], $status);
        }
        
        
        fputcsv($f, $lineData, $delimiter);
    }


    function convert_to_f($c) {
        return $f = ($c * 9/5) + 32;
    }

    function round_value($value) {
        return round($value, 2);
    }
    
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$filename.';');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    
    //output all remaining data on a file pointer
    fpassthru($f);
//}
exit;