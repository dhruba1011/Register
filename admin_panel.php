
<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .header {
      background-color: #007BFF;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      max-width: 800px;
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .admin-links {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
    }

    .admin-links a {
      display: block;
      padding: 15px;
      background-color: #007BFF;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .admin-links a:hover {
      background-color: #0056b3;
    }

    @media (max-width: 500px) {
      .admin-links a {
        padding: 12px;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Admin Dashboard</h1>
</div>

<div class="container">
  <h2>Welcome, Admin</h2>
  <div class="admin-links">
    <a href="setting.php">Change Settings</a>
    <a href="viewall.php">Manage Students</a>
    <a href="examentry.php">Schedule Exams</a>
    <a href="#">Details of Students</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

</body>
</html>
