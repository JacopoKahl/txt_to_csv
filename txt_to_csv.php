<?php
/**********************************************
This script retrieves data from a TXT file
via FTP, then export it and convert data inside
a CSV.
***********************************************/

//Define local file and remote server file
$local_file = 'your_path/local.txt';
$server_file = 'your_path/local.txt';

//Variables to access FTP
$ftp_server = ''; //Put the server adress
$ftp_user_name = ''; //Put FTP username
$ftp_user_pass = ''; //Put FTP password

//Setup FTP connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// download the $server_file and save it in $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    echo "Local file succesfully written $local_file\n";
} else {
    echo "Problem with the file\n";
}

// close the connection
ftp_close($conn_id);

/////////////////////////////////////////////////////////////////////////

//Take txt file in input
$content = file_get_contents($local_file);

//Segment content rows
$rows = explode("\n", $content);
$csv_rows = array();
$header = array('SKU', 'NAME', 'QTY'); //check if a header is alredy available

//Open the CSV file
$csv_file = fopen("your_path/local.csv", "w");
fputcsv($csv_file, $header); 

//Add columns separated with commas
//Link header values and convert 
//float number values to integer
//put inside the CSV
foreach($rows as $row) {
    $columns = explode(";", $row);
    $first = "SKU_" . $columns[0];
    $second = isset($columns[1]) ? $columns[1] : "";
    $num = str_replace(",", ".", end($columns));
    $third = round(floatval($num), 0, PHP_ROUND_HALF_UP);
    $result = array($first, $second, $third);

    fputcsv($csv_file, $result);
}

fclose($csv_file);
