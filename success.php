<?php
session_start();

if (empty($_SESSION['reg_details'])) {
    header('Location: register.php');
    exit;
}
// Last change today 
$d = $_SESSION['reg_details']; // Do NOT unset here — keep it for JS use
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Registration Successful</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #28a745; }
        .details p { margin: 10px 0; }
        .details strong { width: 120px; display: inline-block; color: #333; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px; text-decoration: none; text-align: center; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Successful!</h2>
        <div class="details" id="regDetails">
            <?php foreach ($d as $key => $value): ?>
                <p>
                    <strong><?php echo ucfirst(str_replace('_',' ',$key)); ?>:</strong>
                    <?php echo htmlspecialchars($value); ?>
                </p>
            <?php endforeach; ?>
        </div>
        <button class="btn" onclick="downloadTextFile()">Download as Text</button>
    </div>
    <script>
        function downloadTextFile() {
            const data = <?php echo json_encode($d); ?>;
            let content = "Registration Successful!\n\n";
            for (const key in data) {
                const label = key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                content += `${label}: ${data[key]}\n`;
            }

            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = 'registration_details.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            // alert ("Download Done !! \nNow close this Form........");

        }
    </script>
</body>
</html>
