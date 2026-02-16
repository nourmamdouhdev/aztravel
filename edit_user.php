<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_manager();

$pageTitle = 'Edit User | AZTravel';
$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare('SELECT id, username, role FROM users WHERE id = :id');
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    echo 'User not found.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $role = $_POST['role'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $role === '') {
            $errors[] = 'Please fill in all required fields.';
        }

        if (!in_array($role, ['manager', 'staff'], true)) {
            $errors[] = 'Invalid role selected.';
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username AND id != :id LIMIT 1');
        $stmt->execute(['username' => $username, 'id' => $id]);
        if ($stmt->fetch()) {
            $errors[] = 'Username already exists.';
        }

        if (!empty($password) && strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if (empty($errors)) {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET username = :username, role = :role, password_hash = :password_hash WHERE id = :id');
                $stmt->execute([
                    'username' => $username,
                    'role' => $role,
                    'password_hash' => $hash,
                    'id' => $id,
                ]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = :username, role = :role WHERE id = :id');
                $stmt->execute([
                    'username' => $username,
                    'role' => $role,
                    'id' => $id,
                ]);
            }

            if ((int)($_SESSION['admin_id'] ?? 0) === $id) {
                $_SESSION['admin_name'] = $username;
                $_SESSION['admin_role'] = $role;
            }

            header('Location: users.php');
            exit;
        }

        $user['username'] = $username;
        $user['role'] = $role;
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard reveal">
    <div class="container form-page">
        <h2>Edit User</h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <label>
                Username *
                <input type="text" name="username" value="<?php echo e($user['username']); ?>" required>
            </label>
            <label>
                Role *
                <select name="role" required>
                    <option value="staff" <?php echo ($user['role'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                    <option value="manager" <?php echo ($user['role'] === 'manager') ? 'selected' : ''; ?>>Manager</option>
                </select>
            </label>
            <label class="full">
                New Password (leave empty to keep current)
                <input type="password" name="password">
            </label>
            <?php if (!empty($errors)): ?>
                <div class="form-error full">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <button class="btn" type="submit">Save Changes</button>
            <a class="btn ghost" href="users.php">Cancel</a>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
