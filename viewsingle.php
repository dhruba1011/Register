<?php
include 'connection.php'; // your DB connection

$search = '';
$data = null;

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    if ($search !== '') {
        $stmt = $conn->prepare("SELECT * FROM registration WHERE regno LIKE CONCAT('%', ?, '%') ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Status</title>
  <style>
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .search-form, .result-container {
      background: white;
      width: 90%;
      max-width: 400px;
      padding: 20px;
      margin-top: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px 15px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 15px;
      box-sizing: border-box;
    }

    button[type="submit"] {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button[type="submit"]:hover {
      background: #218838;
    }

    h2 {
      color: #333;
      margin-bottom: 15px;
    }

    p {
      color: #555;
      font-size: 18px;
      margin-bottom: 25px;
    }

    .back-btn {
      display: inline-block;
      padding: 10px 20px;
      background: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-size: 16px;
      transition: background 0.3s;
    }

    .back-btn:hover {
      background: #0056b3;
    }

    @media (max-width: 480px) {
      .search-form, .result-container {
        padding: 15px;
        margin-top: 15px;
      }

      input[type="text"] {
        font-size: 15px;
      }

      button[type="submit"], .back-btn {
        font-size: 15px;
        padding: 10px;
      }

      p {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

<div class="search-form">
  <form method="get">
    <input type="text" name="search" placeholder="Enter Registration No" value="<?= htmlspecialchars($search) ?>" required>
    <button type="submit">Search</button>
  </form>
</div>

<?php if ($search !== ''): ?>
  <div class="result-container">
    <?php if ($data): ?>
      <h2>Hello, <?= htmlspecialchars($data['sname']) ?>!</h2>
      <p>You are already registered.</p>
    <?php else: ?>
      <h2>Sorry!</h2>
      <p>You are not registered.</p>
    <?php endif; ?>
    <a href="#" class="back-btn">Back to Home</a>
  </div>
<?php endif; ?>

</body>
</html>
