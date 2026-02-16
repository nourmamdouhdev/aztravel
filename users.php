<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_manager();

$pageTitle = 'User Management | AZTravel';

$stmt = $pdo->query('SELECT id, username, role, created_at FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard reveal">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <h2>User Management</h2>
                <p>Managers can add staff, change roles, and reset passwords.</p>
            </div>
            <a class="btn" href="add_user.php">Add User</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="4">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo e($user['username']); ?></td>
                                <td><?php echo e(ucfirst($user['role'])); ?></td>
                                <td><?php echo e($user['created_at']); ?></td>
                                <td class="actions">
                                    <a href="edit_user.php?id=<?php echo (int)$user['id']; ?>">Edit</a>
                                    <?php if ((int)$user['id'] !== (int)($_SESSION['admin_id'] ?? 0)): ?>
                                        <form method="post" action="delete_user.php" onsubmit="return confirm('Delete this user?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                                            <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
                                            <button type="submit">Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <span>Current user</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
