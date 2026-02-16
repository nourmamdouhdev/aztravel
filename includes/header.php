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
        <a class="logo" href="index.php">
            <img src="assets/img/logo.png" alt="AZTravel logo">
            <span class="logo-text">AZTravel</span> 
        </a>
        <nav class="nav">
            <button class="btn ghost currency-btn" type="button">
                <?php echo e(get_currency()); ?>
            </button>
            <a href="index.php">Home</a>
            <a href="religious.php">Religious</a>
            <a href="domestic.php">Domestic</a>
            <a href="international.php">International</a>

            <?php if (is_logged_in()): ?>
                <a href="dashboard.php">Dashboard</a>
                <?php if (is_manager()): ?>
                    <a href="users.php">Users</a>
                <?php endif; ?>
                <div class="user-menu">
                    <button class="user-toggle" type="button" aria-haspopup="true" aria-expanded="false">
                        <?php if (!empty($_SESSION['admin_avatar'])): ?>
                            <img src="<?php echo e($_SESSION['admin_avatar']); ?>" alt="Profile">
                        <?php else: ?>
                            <span class="avatar-fallback"><?php echo e(initials($_SESSION['admin_name'] ?? 'User')); ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="user-dropdown">
                        <div class="user-meta">
                            <strong><?php echo e($_SESSION['admin_name'] ?? 'User'); ?></strong>
                            <span><?php echo e($_SESSION['admin_role'] ?? ''); ?></span>
                        </div>
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a class="btn" href="login.php">Admin Login</a>
            <?php endif; ?>
        </nav>
        <button class="nav-toggle" aria-label="Open menu">Menu</button>
    </div>
</header>
<div class="modal" id="currency-modal" aria-hidden="true">
    <div class="modal-backdrop" data-close="true"></div>
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="currency-title">
        <div class="modal-header">
            <h3 id="currency-title">Select your currency</h3>
            <button class="modal-close" type="button" data-close="true">×</button>
        </div>
        <p class="modal-subtitle">Prices will be converted to the currency you select.</p>
        <form class="currency-form" method="post" action="set_currency.php">
            <label>
                Currency
                <select name="currency" required>
                    <?php foreach ($CURRENCIES as $code => $info): ?>
                        <option value="<?php echo e($code); ?>" <?php echo ($code === get_currency()) ? 'selected' : ''; ?>>
                            <?php echo e($info['label'] . ' (' . $code . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="btn" type="submit">Apply</button>
        </form>
    </div>
</div>
<main>
