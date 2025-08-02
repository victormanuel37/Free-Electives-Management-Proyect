<?php
header('Content-Type: text/html; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/courses.css">
    <title>UPRA Free Electives</title>
</head>
<body>
    <header id="generalHeader">
        <nav>
            <ul>
                <?php if (isset($_SESSION['role'])): ?>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="departments.php">Departments</a></li>
                    <li><a href="terms.php">Terms</a></li>
                    <li><a href="term_offering.php">Course offering</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="../controllers/loginController.php?action=logout">Log out</a></li>
                <?php else: ?>
                    <h1>Sistema de Gestión de Electivas Libres</h1>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
