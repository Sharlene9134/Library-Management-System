<?php
session_start();

$isAdmin = (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'Admin');
include 'conn.php';

function e(?string $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

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
        <h2>Transactions</h2>
    </main>
</body>
</html>