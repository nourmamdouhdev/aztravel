<?php
if (!isset($pageTitle)) {
    $pageTitle = 'AZTravel';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Space+Grotesk:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="logo" href="index.php">AZTravel</a>
        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="religious.php">Religious</a>
            <a href="domestic.php">Domestic</a>
            <a href="international.php">International</a>
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn" href="login.php">Admin Login</a>
            <?php endif; ?>
        </nav>
        <button class="nav-toggle" aria-label="Open menu">Menu</button>
    </div>
</header>
<main>
