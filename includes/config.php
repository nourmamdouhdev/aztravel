<?php
$DB_HOST = 'localhost';
$DB_NAME = 'aztravel';
$DB_USER = 'root';
$DB_PASS = '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$UPLOAD_DIR = __DIR__ . '/../uploads/trips';
$UPLOAD_URL = 'uploads/trips';
$AVATAR_DIR = __DIR__ . '/../uploads/avatars';
$AVATAR_URL = 'uploads/avatars';

$CURRENCIES = [
    'USD' => ['symbol' => '$', 'rate' => 1.0, 'label' => 'US Dollar'],
    'EGP' => ['symbol' => 'E£', 'rate' => 49.5, 'label' => 'Egyptian Pound'],
    'EUR' => ['symbol' => '€', 'rate' => 0.92, 'label' => 'Euro'],
    'GBP' => ['symbol' => '£', 'rate' => 0.79, 'label' => 'Pound Sterling'],
    'SAR' => ['symbol' => 'SAR', 'rate' => 3.75, 'label' => 'Saudi Riyal'],
    'AED' => ['symbol' => 'AED', 'rate' => 3.67, 'label' => 'UAE Dirham'],
];
$DEFAULT_CURRENCY = 'USD';

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database connection failed.';
    exit;
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function upload_trip_image(array $file, array &$errors): ?string {
    global $UPLOAD_DIR, $UPLOAD_URL;

    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Image upload failed.';
        return null;
    }

    if ($file['size'] > 4 * 1024 * 1024) {
        $errors[] = 'Image must be less than 4MB.';
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        $errors[] = 'Only JPG, PNG, or WEBP images are allowed.';
        return null;
    }

    if (!is_dir($UPLOAD_DIR) && !mkdir($UPLOAD_DIR, 0755, true)) {
        $errors[] = 'Upload folder is not writable.';
        return null;
    }

    $filename = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
    $destination = rtrim($UPLOAD_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $errors[] = 'Failed to save uploaded image.';
        return null;
    }

    return rtrim($UPLOAD_URL, '/') . '/' . $filename;
}

function upload_avatar(array $file, array &$errors): ?string {
    global $AVATAR_DIR, $AVATAR_URL;

    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Avatar upload failed.';
        return null;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $errors[] = 'Avatar must be less than 2MB.';
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        $errors[] = 'Only JPG, PNG, or WEBP avatars are allowed.';
        return null;
    }

    if (!is_dir($AVATAR_DIR) && !mkdir($AVATAR_DIR, 0755, true)) {
        $errors[] = 'Avatar upload folder is not writable.';
        return null;
    }

    $filename = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
    $destination = rtrim($AVATAR_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $errors[] = 'Failed to save avatar.';
        return null;
    }

    return rtrim($AVATAR_URL, '/') . '/' . $filename;
}

function initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach ($parts as $part) {
        if ($part !== '') {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }
    }
    return $initials !== '' ? $initials : 'U';
}

function get_currency(): string {
    global $CURRENCIES, $DEFAULT_CURRENCY;
    $currency = $_SESSION['currency'] ?? $DEFAULT_CURRENCY;
    return array_key_exists($currency, $CURRENCIES) ? $currency : $DEFAULT_CURRENCY;
}

function format_price(float $amountUsd): string {
    global $CURRENCIES;
    $currency = get_currency();
    $rate = $CURRENCIES[$currency]['rate'] ?? 1.0;
    $symbol = $CURRENCIES[$currency]['symbol'] ?? '$';
    $converted = $amountUsd * $rate;
    return $symbol . number_format($converted, 2);
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): bool {
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}
