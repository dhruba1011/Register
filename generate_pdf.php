<?php
// Start session and get registration details
// dhrubajyoti SINGHA 
//changed from mobile
session_start();

if (empty($_SESSION['reg_details'])) {
    header('Location: register.php');
    exit;
}

$d = $_SESSION['reg_details'];

// Create the content of the text file
$file_content = "Registration Successful!\n\n";

foreach ($d as $key => $value) {
    $label = ucfirst(str_replace('_', ' ', $key));
    $file_content .= "$label: $value\n";
}

// Set the filename
$filename = 'registration_details.txt';

// Set headers to trigger file download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($file_content));

// Output the content to the browser as a text file
echo $file_content;

exit;
