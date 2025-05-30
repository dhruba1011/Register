<?php
$servername = "localhost"; // or your server IP
$username = "root";        // your database username
$password = "";            // your database password
$dbname = "registration2"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//  echo "Connected successfully";

// Set time zone to IST
mysqli_query($conn, "SET time_zone = '+05:30'");

?>
