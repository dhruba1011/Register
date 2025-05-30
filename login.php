<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Replace with secure validation (e.g., database lookup)
    $valid_username = "aa";
    $valid_password = "aa"; // Store hashed passwords in production!

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_username"] = $username;
        header("Location: admin_panel.php"); // Redirect to admin panel
        exit();
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href = 'index.php';</script>";
    }
}
?>