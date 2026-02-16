<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'My Profile | AZTravel';
$errors = [];
$success = null;

$userId = (int)($_SESSION['admin_id'] ?? 0);
$stmt = $pdo->prepare('SELECT id, username, full_name, avatar_url, password_hash FROM users WHERE id = :id');
$stmt->execute(['id' => $userId]);
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
        $fullName = trim($_POST['full_name'] ?? '');
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($fullName === '') {
            $errors[] = 'Please enter your full name.';
        }

        $changePassword = ($current !== '' || $new !== '' || $confirm !== '');
        if ($changePassword) {
            if ($current === '' || $new === '' || $confirm === '') {
                $errors[] = 'Please fill in all password fields.';
            }

            if (!password_verify($current, $user['password_hash'])) {
                $errors[] = 'Current password is incorrect.';
            }

            if (strlen($new) < 6) {
                $errors[] = 'New password must be at least 6 characters.';
            }

            if ($new !== $confirm) {
                $errors[] = 'New password and confirmation do not match.';
            }
        }

        $uploadedAvatar = upload_avatar($_FILES['avatar_file'] ?? [], $errors);

        if (empty($errors)) {
            $fields = ['full_name' => $fullName];
            $sqlParts = ['full_name = :full_name'];

            if ($uploadedAvatar) {
                $fields['avatar_url'] = $uploadedAvatar;
                $sqlParts[] = 'avatar_url = :avatar_url';
                $_SESSION['admin_avatar'] = $uploadedAvatar;
            }

            if ($changePassword) {
                $fields['password_hash'] = password_hash($new, PASSWORD_DEFAULT);
                $sqlParts[] = 'password_hash = :password_hash';
            }

            $fields['id'] = $userId;
            $stmt = $pdo->prepare('UPDATE users SET ' . implode(', ', $sqlParts) . ' WHERE id = :id');
            $stmt->execute($fields);

            $_SESSION['admin_name'] = $fullName;
            $success = 'Profile updated successfully.';
            $user['full_name'] = $fullName;
            if ($uploadedAvatar) {
                $user['avatar_url'] = $uploadedAvatar;
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard reveal">
    <div class="container form-page">
        <h2>My Profile</h2>
        <p>Update your name, photo, or password.</p>
        <div class="profile-preview">
            <?php if (!empty($user['avatar_url'])): ?>
                <img src="<?php echo e($user['avatar_url']); ?>" alt="Profile photo">
            <?php else: ?>
                <div class="avatar-fallback"><?php echo e(initials($user['full_name'] ?? $user['username'])); ?></div>
            <?php endif; ?>
        </div>
        <form method="post" class="form-grid" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <label class="full">
                Full Name
                <input type="text" name="full_name" value="<?php echo e($user['full_name'] ?? $user['username']); ?>" required>
            </label>
            <label class="full">
                Current Password
                <input type="password" name="current_password" required>
            </label>
            <label class="full">
                New Password
                <input type="password" name="new_password" required>
            </label>
            <label class="full">
                Confirm New Password
                <input type="password" name="confirm_password" required>
            </label>
            <label class="full">
                Profile Photo (JPG/PNG/WEBP, max 2MB)
                <input type="file" name="avatar_file" accept="image/*">
            </label>
            <?php if (!empty($errors)): ?>
                <div class="form-error full">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($success): ?>
                <div class="form-success full">
                    <p><?php echo e($success); ?></p>
                </div>
            <?php endif; ?>
            <button class="btn" type="submit">Update Password</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
