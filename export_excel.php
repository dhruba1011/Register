<?php
include 'connection.php'; 

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=students_data.xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "SELECT * FROM registration";
$result = $conn->query($sql);

echo "<table border='1'>";
echo "<tr>
        <th>ID</th><th>TimeStamp</th><th>Session</th><th>Course</th><th>Batch</th><th>Reg No</th><th>Class Days</th><th>Class Timing</th>
        <th>Name</th><th>Father Name</th><th>Gender</th><th>Cast</th><th>Phone1</th><th>Phone2</th><th>DOB</th>
        <th>Last Exam Passed</th><th>% Madhyamik</th><th>% H.S.</th>
      </tr>";


while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
