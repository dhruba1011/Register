<?php
// Start session (for admin login tracking)
session_start();

// If admin is already logged in, redirect to admin panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_panel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habra YCTC</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f1f1f1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #0056b3;
      color: white;
      text-align: center;
      padding: 15px;
      font-size: 40px;
      font-weight: bold;
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h1 {
      margin-bottom: 20px;
      color: #333;
    }

    .btn {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 12px;
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    footer {
      background-color: #343a40;
      color: white;
      text-align: center;
      padding: 12px;
      font-size: 14px;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 400px;
      text-align: left;
    }

    .modal-content input[type="text"],
    .modal-content input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: black;
    }

    @media (max-width: 500px) {
      .container {
        padding: 20px;
      }

      .btn {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<header>
  Habra Youth Computer Training Centre
</header>

<div class="main">
  <div class="container">
    <h1>Welcome</h1>
    <a href="register.php" class="btn" target="_blank">Registration</a>
    <a href="viewexam.php" class="btn" target="_blank">View Exam Details</a>
    <button class="btn" onclick="openModal()">Admin Panel</button>
  </div>
</div>

<footer>
  &copy; <?php echo date("Y"); ?> Habra Youth Computer Training Centre.<br> All rights reserved.
</footer>

<!-- Admin Modal -->
<div id="adminModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Admin Login</h2>
    <form action="login.php" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById('adminModal').style.display = 'block';
  }

  function closeModal() {
    document.getElementById('adminModal').style.display = 'none';
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
    const modal = document.getElementById('adminModal');
    if (event.target === modal) {
      closeModal();
    }
  }
</script>

</body>
</html>