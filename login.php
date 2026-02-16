<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Admin Login | AZTravel';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT id, username, password_hash, role, full_name, avatar_url FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'] ?: $user['username'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_avatar'] = $user['avatar_url'];
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="auth reveal">
    <div class="container auth-card">
        <h2>Admin Login</h2>
        <p>Sign in to manage trips and schedules.</p>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <label>
                Username
                <input type="text" name="username" required>
            </label>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            <?php if ($error): ?>
                <p class="form-error"><?php echo e($error); ?></p>
            <?php endif; ?>
            <button class="btn" type="submit">Login</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
