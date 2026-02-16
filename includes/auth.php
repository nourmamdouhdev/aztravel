<?php
require_once __DIR__ . '/config.php';

function is_logged_in(): bool {
    return !empty($_SESSION['admin_id']);
}

function is_manager(): bool {
    return !empty($_SESSION['admin_role']) && in_array($_SESSION['admin_role'], ['manager', 'admin'], true);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_manager(): void {
    if (!is_logged_in() || !is_manager()) {
        header('Location: dashboard.php');
        exit;
    }
}
