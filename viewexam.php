<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>YCTC Student Exam Search</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: sans-serif;
      background: #f0f0f0;
      padding: 20px;
    }
    
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .table th {
      text-align: right;
      padding-right: 10px;
    }
    
    .note {
      text-align: center;
      color: red;
      font-weight: bold;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <div class="card-header bg-primary text-white text-center">
      <h6 class="mb-0">Youth Computer Training Centre</h6>
    </div>
    <div class="card-body">
      <form method="get" class="mb-3">
        <div class="input-group">
          <input type="text" name="regno" class="form-control" placeholder="Enter Reg. No." required>
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>

      <?php
      if (isset($_GET['regno'])) {
          $regno = $conn->real_escape_string($_GET['regno']);
          $sql = "SELECT * FROM exam WHERE regno LIKE '%$regno%'";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $formatted_date = date("d/m/Y", strtotime($row['exam_date']));
                  $formatted_time = date("h:i A", strtotime($row['exam_time']));

                  echo "<div class='table-responsive mb-3'>";
                  echo "<table class='table table-bordered'>
                          <tr><th>Batch</th><td>{$row['batch']}</td></tr>
                          <tr><th>Reg. No.</th><td>{$row['regno']}</td></tr>
                          <tr><th>Login ID</th><td>{$row['loginid']}</td></tr>
                          <tr><th>Name</th><td>{$row['sname']}</td></tr>
                          <tr><th>Father's Name</th><td>{$row['fname']}</td></tr>
                          <tr><th>Course</th><td>{$row['course']}</td></tr>
                          <tr><th>Exam Date</th><td>{$formatted_date}</td></tr>
                          <tr><th>Exam Time</th><td>{$formatted_time}</td></tr>
                        </table>";
                  echo "<p class='note'>Must bring I-Card and ₹170/- Receipt during Exam.</p>";
                  echo "</div>";
              }
          } else {
              echo "<div class='alert alert-warning text-center'>No records found.</div>";
          }
      }
      ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>