<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_manager();

$pageTitle = 'Add User | AZTravel';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $role = $_POST['role'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $role === '' || $password === '') {
            $errors[] = 'Please fill in all required fields.';
        }

        if (!in_array($role, ['manager', 'staff'], true)) {
            $errors[] = 'Invalid role selected.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            $errors[] = 'Username already exists.';
        }

        if (empty($errors)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)');
            $stmt->execute([
                'username' => $username,
                'password_hash' => $hash,
                'role' => $role,
            ]);
            header('Location: users.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard reveal">
    <div class="container form-page">
        <h2>Add User</h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <label>
                Username *
                <input type="text" name="username" value="<?php echo e($_POST['username'] ?? ''); ?>" required>
            </label>
            <label>
                Role *
                <select name="role" required>
                    <option value="">Select</option>
                    <option value="staff" <?php echo (($_POST['role'] ?? '') === 'staff') ? 'selected' : ''; ?>>Staff</option>
                    <option value="manager" <?php echo (($_POST['role'] ?? '') === 'manager') ? 'selected' : ''; ?>>Manager</option>
                </select>
            </label>
            <label>
                Password *
                <input type="password" name="password" required>
            </label>
            <?php if (!empty($errors)): ?>
                <div class="form-error full">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <button class="btn" type="submit">Create User</button>
            <a class="btn ghost" href="users.php">Cancel</a>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
