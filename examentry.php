<?php


session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: index.php");
    exit();
}


include 'connection.php';

$message = "";

if (isset($_POST['upload'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (!empty($file)) {
        $handle = fopen($file, 'r');
        if ($handle !== false) {
            // Clear old data
            $conn->query("DELETE FROM exam");

            // Skip header row
            fgetcsv($handle);

            $stmt = $conn->prepare("INSERT INTO exam (batch, regno, loginid, sname, fname, course, exam_date, exam_time) VALUES (?, ?, ?, ?, ?, ?,?,?)");

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $batch = $data[0];
                $regno = $data[1];
                $loginid = $data[2];
                $name = $data[3];
                $fat_name = $data[4];
                $course = $data[5];
                $exam_date = date('Y-m-d', strtotime($data[6]));
                $exam_time = date('H:i:s', strtotime($data[7]));

                $stmt->bind_param("ssssssss", $batch, $regno, $loginid, $name, $fat_name, $course, $exam_date, $exam_time);
                $stmt->execute();
            }

            fclose($handle);
            $message = "<div class='alert alert-success'>CSV imported successfully. Previous data cleared.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error reading the CSV file.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please upload a valid CSV file.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import CSV to MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .narrow-card {
            max-width: 600px;
            margin: auto;
            margin-top: 60px;
        }
    </style>
</head>
<body class="bg-light">

<div class="card narrow-card shadow-lg">
    <div class="card-header bg-success text-white text-center">
        <h4>Upload CSV File to Import Student Data</h4>
    </div>
    <div class="card-body">
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Select CSV File</label>
                <p>CSV File Format - batch, regno, loginid, name, father_name, course, exam_date, exam_time </p>
                <input type="file" name="csv_file" class="form-control" accept=".csv" required>
            </div>
            <button type="submit" name="upload" class="btn btn-success w-100">Import CSV</button>
        </form>
    </div>
</div>

</body>
</html>
