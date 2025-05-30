<?php
session_start();

// Check if session contains registration details
if (!isset($_SESSION['reg_details']) || !is_array($_SESSION['reg_details'])) {
    http_response_code(400);
    echo "No registration data found.";
    exit;
}

$d = $_SESSION['reg_details'];

// Optional: unset session if you want one-time download
// unset($_SESSION['reg_details']);

// Build tab-delimited text content
$file_content = "Field\tValue\n"; // header row
foreach ($d as $key => $value) {
    $label = ucfirst(str_replace('_', ' ', $key));
    $file_content .= "$label\t$value\n";
}

// Clean the output buffer
if (ob_get_length()) {
    ob_clean();
}

// Send headers to force file download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="registration_details.txt"');
header('Content-Length: ' . strlen($file_content));
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output the file content
echo $file_content;
exit;
