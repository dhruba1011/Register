<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: index.php");
    exit();
}

include 'connection.php';

// Initialize all fields with empty string
$fields = [
    'session' => '',
    'course' => '',
    'cita' => '',
    'dita' => '',
    'adita' => '',
    'cdta' => '',
    'ddta' => '',
    'cfas' => '',
    'dfas' => '',
    'adfas' => '',
    'cdtp' => '',
    'ddtp' => ''
];

// Load existing data from database
$result = $conn->query("SELECT * FROM settings");
if ($result && $result->num_rows > 0) {
    // Group all values by field name
    $fieldValues = [];
    foreach (array_keys($fields) as $field) {
        $fieldValues[$field] = [];
    }
    
    // Collect all values from each row
    while ($row = $result->fetch_assoc()) {
        foreach ($fields as $field => $value) {
            if (isset($row[$field]) && $row[$field] !== '') {
                $fieldValues[$field][] = $row[$field];
            }
        }
    }
    
    // Convert to comma-separated strings
    foreach ($fields as $field => $value) {
        $fields[$field] = implode(', ', $fieldValues[$field]);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission here
    // [Your existing form submission code]
    
    // After successful save, redirect to avoid resubmission
    $_SESSION['success_message'] = "Settings saved successfully!";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Settings Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .help-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }
        .btn-submit {
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 15px;
        }
        .success-message {
            color: green;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Settings Management</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?= $_SESSION['success_message'] ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <?php foreach ($fields as $field => $value): ?>
                <div class="form-group">
                    <label for="<?= $field ?>"><?= strtoupper($field) ?></label>
                    <input type="text" 
                           id="<?= $field ?>" 
                           name="<?= $field ?>" 
                           value="<?= htmlspecialchars($value) ?>"
                           placeholder="Enter comma-separated values">
                    <div class="help-text">
                        Separate multiple values with commas (e.g., "Value 1, Value 2, Value 3")
                    </div>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn-submit">Save Settings</button>
        </form>
    </div>
</body>
</html>