<?php
session_start();

// ---- Auth guard (uncomment & adjust for your system) ----
// if (!isset($_SESSION['usertype'])) { header('Location: login2.php'); exit; }

$isAdmin = (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'Admin');
include 'conn.php';

function e(?string $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// ---- Fetch Dashboard Statistics ----
$stats = [
    'total_books'     => 0,
    'available_books' => 0,
    'ongoing_tx'      => 0,
    'overdue_tx'      => 0,
    'borrowers'       => 0
];

// 1. Book Stats
$r = mysqli_query($conn, "SELECT Status, COUNT(*) as count FROM book GROUP BY Status");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $stats['total_books'] += $row['count'];
        if ($row['Status'] === 'Available') $stats['available_books'] = $row['count'];
    }
}

// 2. Ongoing Transactions & Overdue
$today = date('Y-m-d');
$r = mysqli_query($conn, "SELECT Due_date FROM transaction WHERE Status='Ongoing'");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $stats['ongoing_tx']++;
        if (!empty($row['Due_date']) && $today > $row['Due_date']) {
            $stats['overdue_tx']++;
        }
    }
}

// 3. Borrower Count
$r = mysqli_query($conn, "SELECT COUNT(*) as count FROM borrower");
if ($r) {
    $row = mysqli_fetch_assoc($r);
    $stats['borrowers'] = $row['count'] ?? 0;
}

// ---- Fetch Recent Overdue Transactions for Quick View ----
$overdueQuery = "
    SELECT t.Transaction_ID, t.Book_ID, t.Borrower_ID, t.Due_date, b.Title 
    FROM transaction t
    JOIN book b ON t.Book_ID = b.Book_ID
    WHERE t.Status = 'Ongoing' AND t.Due_date < '$today'
    ORDER BY t.Due_date ASC
    LIMIT 5
";
$overdueResult = mysqli_query($conn, $overdueQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Header -->
    <header>
        <h1>Library Management System</h1>
    </header>

    <!-- Sidebar -->
    <aside>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="transaction.php">Transactions</a>
            <a href="books.php">Books</a>
            <a href="borrower.php">Borrowers</a>
            <a href="staff.php">Staff</a>
            <a href="reports.php">Reports</a>
            <a href="archives.php">Archives</a>
            <?php if ($isAdmin): ?>
                <a href="admindisplay.php">User Management</a>
            <?php endif; ?>
            <a href="login2.php">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <h2>Dashboard</h2>
    </main>
</body>
</html>