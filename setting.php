<?php
include 'connection.php';

if (isset($_GET['download'])) {
    // DOWNLOAD CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=settings_export.csv');

    $output = fopen('php://output', 'w');

    // Get column names
    $result = $conn->query("SHOW COLUMNS FROM settings");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    fputcsv($output, $columns);

    // Get data
    $data = $conn->query("SELECT * FROM settings");
    while ($row = $data->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

if (isset($_POST['upload'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($file, "r")) !== FALSE) {
            $headers = fgetcsv($handle);
            $colCount = count($headers);

            $conn->query("DELETE FROM settings");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) != $colCount) continue;

                $escaped = array_map(function($val) use ($conn) {
                    return $val === '' ? 'NULL' : "'" . $conn->real_escape_string($val) . "'";
                }, $data);

                $sql = "INSERT INTO settings (`" . implode('`,`', $headers) . "`) VALUES (" . implode(",", $escaped) . ")";
                $conn->query($sql);
            }

            fclose($handle);
            $message = "✅ Upload and replace successful.";
        } else {
            $message = "❌ Error reading the file.";
        }
    } else {
        $message = "❌ File upload error.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings Table Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 40px;
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        a.button {
            display: inline-block;
            text-decoration: none;
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        a.button:hover {
            background: #45a049;
        }
        form {
            margin-top: 25px;
        }
        input[type="file"] {
            padding: 8px;
            margin: 10px 0;
            width: 100%;
        }
        button {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #1e88e5;
        }
        .message {
            margin: 20px 0;
            padding: 12px;
            border-radius: 6px;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
        }
        .error {
            background: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Change Settings for New Sessions</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <p>
        <a href="?download=1" class="button">📥 Download Settings CSV</a>
    </p>

    <form method="post" enctype="multipart/form-data">
        <label for="csv_file">Select CSV to upload and replace:</label><br>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required><br>
        <button type="submit" name="upload">📤 Upload and Replace</button>
    </form>
</div>
</body>
</html>
