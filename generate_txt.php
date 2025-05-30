<?php
session_start();

// Check if session contains registration details
if (!isset($_SESSION['reg_details']) || !is_array($_SESSION['reg_details'])) {
    // Optionally log this or handle the error gracefully
    http_response_code(400);
    echo "No registration data found.";
    exit;
}

$d = $_SESSION['reg_details'];

// You can optionally clear the session here if needed:
// unset($_SESSION['reg_details']); // Uncomment if you want to clear after download

// Build the text content
$file_content = "Registration Successful!\n\n";
foreach ($d as $key => $value) {
    $label = ucfirst(str_replace('_', ' ', $key));
    $file_content .= "$label: $value\n";
}

// Clean the output buffer in case anything was printed before
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
