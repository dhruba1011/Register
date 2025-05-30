<?php
// session_start();
// if (!isset($_SESSION["admin_logged_in"])) {
//     header("Location: index.php");
//     exit();
// }

include 'connection.php';

// Handle CSV Download
if (isset($_GET['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="settings_export_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, array_keys($fields));
    
    // Write data
    $result = $conn->query("SELECT * FROM settings");
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

// Handle CSV Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['csvfile'])) {
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION));
    
    // Check if file is CSV
    if ($fileType != "csv") {
        $_SESSION['error_message'] = "Only CSV files are allowed.";
        $uploadOk = 0;
    }
    
    // Check file size (5MB max)
    if ($_FILES['csvfile']['size'] > 5000000) {
        $_SESSION['error_message'] = "File is too large (max 5MB).";
        $uploadOk = 0;
    }
    
    if ($uploadOk) {
        // Process CSV file
        if (($handle = fopen($_FILES['csvfile']['tmp_name'], "r")) !== FALSE) {
            // Get headers
            $headers = fgetcsv($handle);
            
            // Delete existing data
            $conn->query("TRUNCATE TABLE settings");
            
            // Prepare insert statement
            $placeholders = implode(',', array_fill(0, count($headers), '?'));
            $stmt = $conn->prepare("INSERT INTO settings (" . implode(',', $headers) . ") VALUES ($placeholders)");
            
            // Bind parameters dynamically
            $params = [];
            $types = str_repeat('s', count($headers));
            
            // Read data rows
            while (($data = fgetcsv($handle)) !== FALSE) {
                $stmt->bind_param($types, ...$data);
                $stmt->execute();
            }
            
            fclose($handle);
            $stmt->close();
            
            $_SESSION['success_message'] = "CSV data imported successfully!";
        } else {
            $_SESSION['error_message'] = "Error reading CSV file.";
        }
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// [Rest of your existing code for displaying the form]
?>

<!-- Add to your existing HTML form -->
<div class="csv-actions" style="margin: 20px 0; text-align: center;">
    <a href="?download=1" class="btn btn-primary" style="padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">
        Download as CSV
    </a>
    
    <form method="post" enctype="multipart/form-data" style="display: inline-block;">
        <input type="file" name="csvfile" accept=".csv" required style="display: none;" id="csvUpload">
        <label for="csvUpload" class="btn btn-secondary" style="padding: 10px 15px; background: #6c757d; color: white; border-radius: 4px; cursor: pointer;">
            Upload CSV
        </label>
        <button type="submit" style="padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Import Data
        </button>
    </form>
</div>

<!-- Add this to your existing error/success message display -->
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="error-message" style="color: red; padding: 10px; margin-bottom: 20px; text-align: center; font-weight: bold;">
        <?= $_SESSION['error_message'] ?>
        <?php unset($_SESSION['error_message']); ?>
    </div>
<?php endif; ?>