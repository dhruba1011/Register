<?php

session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: index.php");
    exit();
}

include 'connection.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$per_page = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Query to get total records
$total_sql = "SELECT COUNT(*) AS total FROM registration WHERE 
              sname LIKE '%$search%' OR 
              regno LIKE '%$search%' OR 
              batch LIKE '%$search%'";
$total_result = $conn->query($total_sql);
$total = $total_result->fetch_assoc()['total'];

// Query to get limited records
$sql = "SELECT * FROM registration WHERE 
        sname LIKE '%$search%' OR 
        regno LIKE '%$search%' OR 
        batch LIKE '%$search%' 
        ORDER BY id DESC 
        LIMIT $offset, $per_page";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }
        table th, table td {
            padding: 4px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #e6f7ff;
        }
        table th {
            background-color: #ddd;
            font-weight: bold;
        }
        form {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        form input[type="text"] {
            padding: 4px;
            font-size: 14px;
            width: 200px;
        }
        form select, form button, form a {
            padding: 4px 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            text-decoration: none;
            color: black;
            border-radius: 4px;
        }
        form button {
            background-color: #005f73;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0a9396;
        }
        .pagination {
            margin-top: 5px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .pagination a {
            padding: 6px 12px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            font-size: 14px;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .pagination a.active {
            font-weight: bold;
            background-color: #0077b6;
            color: white;
            border-color: #0077b6;
        }
        td.cformat, th.cformat {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>

    <h2>Registered Students</h2>

    <form method="get" action="viewall.php">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name, reg. no, batch">
        <select name="limit" onchange="this.form.submit()">
            <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
        </select>
        <button type="submit">Search</button>
        <a href="export_excel.php">Export to Excel</a>
        <!-- <a href="import_sql.php" onclick="return confirm('Are you sure you want to import data.sql?')" style="background-color: #94d2bd;">Import SQL</a> -->
    </form>

    <div id="results">
    <table>
        <tr>
            <th>ID</th>
            <th class="cformat">TimeStamp</th>
            <th class="cformat">Course</th>
            <th>Batch</th>
            <th class="cformat">Reg No</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>Gender</th>
            <th>Phone 1</th>
            <th>Phone 2</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) {
    // Convert timestamp to IST
         if (isset($row['timestamp'])) {
            $utc_time = new DateTime($row['timestamp'], new DateTimeZone('UTC'));
            $utc_time->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $row['timestamp'] = $utc_time->format("d M Y, h:i A");
         }
        ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td class="cformat"><?= $row['timestamp'] ?></td>
                <td class="cformat"><?= $row['course'] ?></td>
                <td><?= $row['batch'] ?></td>
                <td class="cformat"><?= $row['regno'] ?></td>
                <td><?= $row['sname'] ?></td>
                <td><?= $row['fname'] ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['phone1'] ?></td>
                <td><?= $row['phone2'] ?></td>
            </tr>
        <?php } ?>
    </table>
    </div>        

    <!-- Pagination -->
    <?php
    $total_pages = ceil($total / $per_page);
    if ($total_pages > 1) {
        echo '<div class="pagination">';

        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='?search=" . urlencode($search) . "&limit=$per_page&page=$prev'>&laquo; Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            $activeClass = $i == $page ? 'active' : '';
            echo "<a class='$activeClass' href='?search=" . urlencode($search) . "&limit=$per_page&page=$i'>$i</a>";
        }

        if ($page < $total_pages) {
            $next = $page + 1;
            echo "<a href='?search=" . urlencode($search) . "&limit=$per_page&page=$next'>Next &raquo;</a>";
        }

        echo '</div>';
    }
    ?>

    <p style="margin-top: 10px; font-weight: bold;">Total Student(s): <?= $total ?></p>

    <script>
    const searchInput = document.querySelector('input[name="search"]');
    const limitSelect = document.querySelector('select[name="limit"]');
    const resultsContainer = document.getElementById('results');

    let debounceTimer = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchData, 300);
    });

    function fetchData() {
        const search = encodeURIComponent(searchInput.value);
        const limit = limitSelect.value;
        fetch(`view_students.php?search=${search}&limit=${limit}`)
            .then(res => res.text())
            .then(data => {
                const parser = new DOMParser();
                const htmlDoc = parser.parseFromString(data, 'text/html');
                const newResults = htmlDoc.getElementById('results');
                resultsContainer.innerHTML = newResults.innerHTML;
            });
    }
    </script>

</body>
</html>
