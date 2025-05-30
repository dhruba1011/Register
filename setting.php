<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: index.php");
    exit();
}

include 'connection.php';

// Initialize all variables as empty strings
$session = $course = $cita = $dita = $adita = $cdta = $ddta = $cfas = $dfas = $adfas = $cdtp = $ddtp = "";

// Load existing data FIRST, before handling POST
$result = $conn->query("SELECT * FROM settings LIMIT 1");
if ($result && $result->num_rows > 0) {
    $sessions = $courses = $cita_list = $dita_list = $adita_list =
               $cdta_list = $ddta_list = $cfas_list = $dfas_list =
               $adfas_list = $cdtp_list = $ddtp_list = [];


               //echo "Total rows: " . $result->num_rows . "<br>";

    while ($row = $result->fetch_assoc()) {
    if (isset($row['session']) && $row['session'] !== '') $sessions[] = $row['session'];
    if (isset($row['course']) && $row['course'] !== '') $courses[] = $row['course'];
    if (isset($row['cita']) && $row['cita'] !== '') $cita_list[] = $row['cita'];
    if (isset($row['dita']) && $row['dita'] !== '') $dita_list[] = $row['dita'];
    if (isset($row['adita']) && $row['adita'] !== '') $adita_list[] = $row['adita'];
    if (isset($row['cdta']) && $row['cdta'] !== '') $cdta_list[] = $row['cdta'];
    if (isset($row['ddta']) && $row['ddta'] !== '') $ddta_list[] = $row['ddta'];
    if (isset($row['cfas']) && $row['cfas'] !== '') $cfas_list[] = $row['cfas'];
    if (isset($row['dfas']) && $row['dfas'] !== '') $dfas_list[] = $row['dfas'];
    if (isset($row['adfas']) && $row['adfas'] !== '') $adfas_list[] = $row['adfas'];
    if (isset($row['cdtp']) && $row['cdtp'] !== '') $cdtp_list[] = $row['cdtp'];
    if (isset($row['ddtp']) && $row['ddtp'] !== '') $ddtp_list[] = $row['ddtp'];
}

    // Convert arrays to comma-separated strings
    $session  = implode(', ', array_filter($sessions));
    $course   = implode(', ', array_filter($courses));
    $cita     = implode(', ', array_filter($cita_list));
    $dita     = implode(', ', array_filter($dita_list));
    $adita    = implode(', ', array_filter($adita_list));
    $cdta     = implode(', ', array_filter($cdta_list));
    $ddta     = implode(', ', array_filter($ddta_list));
    $cfas     = implode(', ', array_filter($cfas_list));
    $dfas     = implode(', ', array_filter($dfas_list));
    $adfas    = implode(', ', array_filter($adfas_list));
    $cdtp     = implode(', ', array_filter($cdtp_list));
    $ddtp     = implode(', ', array_filter($ddtp_list));
}


// Handle form submission AFTER loading existing data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Safely grab POST or default to empty
    $session      = trim($_POST['session']      ?? '');
    $course       = trim($_POST['course']       ?? '');
    $cita         = trim($_POST['cita']         ?? '');
    $dita         = trim($_POST['dita']         ?? '');
    $adita        = trim($_POST['adita']        ?? '');
    $cdta         = trim($_POST['cdta']         ?? '');
    $ddta         = trim($_POST['ddta']         ?? '');
    $cfas         = trim($_POST['cfas']         ?? '');
    $dfas         = trim($_POST['dfas']         ?? '');
    $adfas        = trim($_POST['adfas']        ?? '');
    $cdtp         = trim($_POST['cdtp']         ?? '');
    $ddtp         = trim($_POST['ddtp']         ?? '');
    
    // Delete old records
    $conn->query("DELETE FROM settings");

    // Split into arrays
    $sessions       = array_map('trim', explode(',', $session));
    $courses        = array_map('trim', explode(',', $course));
    $cita_list      = array_map('trim', explode(',', $cita));
    $dita_list      = array_map('trim', explode(',', $dita));
    $adita_list     = array_map('trim', explode(',', $adita));
    $cdta_list      = array_map('trim', explode(',', $cdta));
    $ddta_list      = array_map('trim', explode(',', $ddta));
    $cfas_list      = array_map('trim', explode(',', $cfas));
    $dfas_list      = array_map('trim', explode(',', $dfas));
    $adfas_list     = array_map('trim', explode(',', $adfas));
    $cdtp_list      = array_map('trim', explode(',', $cdtp));
    $ddtp_list      = array_map('trim', explode(',', $ddtp));
    
    // Determine how many rows we need to insert
    $max_count = max(
        count($sessions), count($courses),
        count($cita_list), count($dita_list), count($adita_list),
        count($cdta_list), count($ddta_list),
        count($cfas_list), count($dfas_list), count($adfas_list),
        count($cdtp_list), count($ddtp_list)
    );

    // Prepare statement for better security
    $stmt = $conn->prepare("INSERT INTO settings (session, course, cita, dita, adita, cdta, ddta, cfas, dfas, adfas, cdtp, ddtp) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Insert each row
    for ($i = 0; $i < $max_count; $i++) {
    // Prepare variables first
    $session_val = $sessions[$i] ?? '';
    $course_val = $courses[$i] ?? '';
    $cita_val = $cita_list[$i] ?? '';
    $dita_val = $dita_list[$i] ?? '';
    $adita_val = $adita_list[$i] ?? '';
    $cdta_val = $cdta_list[$i] ?? '';
    $ddta_val = $ddta_list[$i] ?? '';
    $cfas_val = $cfas_list[$i] ?? '';
    $dfas_val = $dfas_list[$i] ?? '';
    $adfas_val = $adfas_list[$i] ?? '';
    $cdtp_val = $cdtp_list[$i] ?? '';
    $ddtp_val = $ddtp_list[$i] ?? '';
    
    $stmt->bind_param("ssssssssssss",
        $session_val,
        $course_val,
        $cita_val,
        $dita_val,
        $adita_val,
        $cdta_val,
        $ddta_val,
        $cfas_val,
        $dfas_val,
        $adfas_val,
        $cdtp_val,
        $ddtp_val
    );
    $stmt->execute();
}
    
    $stmt->close();
    echo "<p class='success'>Records saved successfully!</p>";
    
    // Refresh the page to show updated data
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Insert Settings</title>
    <link rel="stylesheet" href="style.css">
    <style>
      body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
      }
      form {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
      }
      label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
      }
      input[type="text"] {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      input[type="submit"] {
        background: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
      }
      input[type="submit"]:hover {
        background: #45a049;
      }
      .success {
        color: green;
        font-weight: bold;
        margin: 10px 0;
      }
    </style>
</head>
<body>

  <h2 align="center">Insert Settings Record</h2>

  <form method="post" action="">
    <label>Session</label>
    <input type="text" name="session" value="<?php echo htmlspecialchars($session); ?>">

    <label>Course</label>
    <input type="text" name="course" value="<?php echo htmlspecialchars($course); ?>">

    <label>CITA</label>
    <input type="text" name="cita" value="<?php echo htmlspecialchars($cita); ?>">

    <label>DITA</label>
    <input type="text" name="dita" value="<?php echo htmlspecialchars($dita); ?>">

    <label>ADITA</label>
    <input type="text" name="adita" value="<?php echo htmlspecialchars($adita); ?>">

    <label>CDTA</label>
    <input type="text" name="cdta" value="<?php echo htmlspecialchars($cdta); ?>">

    <label>DDTA</label>
    <input type="text" name="ddta" value="<?php echo htmlspecialchars($ddta); ?>">

    <label>CFAS</label>
    <input type="text" name="cfas" value="<?php echo htmlspecialchars($cfas); ?>">

    <label>DFAS</label>
    <input type="text" name="dfas" value="<?php echo htmlspecialchars($dfas); ?>">

    <label>ADFAS</label>
    <input type="text" name="adfas" value="<?php echo htmlspecialchars($adfas); ?>">

    <label>CDTP</label>
    <input type="text" name="cdtp" value="<?php echo htmlspecialchars($cdtp); ?>">

    <label>DDTP</label>
    <input type="text" name="ddtp" value="<?php echo htmlspecialchars($ddtp); ?>">

    <input type="submit" value="Save Settings">
  </form>

</body>
</html>