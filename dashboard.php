<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Dashboard | AZTravel';

$stmt = $pdo->query('SELECT * FROM trips ORDER BY created_at DESC');
$trips = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard">
    <div class="container">
        <div class="dashboard-header">
            <div>
                <h2>Welcome, <?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></h2>
                <p>Manage AZTravel trips and keep itineraries up to date.</p>
            </div>
            <a class="btn" href="add_trip.php">Add New Trip</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Availability</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($trips)): ?>
                        <tr>
                            <td colspan="7">No trips found. Create your first trip.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($trips as $trip): ?>
                            <tr>
                                <td><?php echo e($trip['name']); ?></td>
                                <td><?php echo ucfirst(e($trip['category'])); ?></td>
                                <td><?php echo format_price((float)$trip['price']); ?></td>
                                <td><?php echo e($trip['duration_days']); ?> days</td>
                                <td><?php echo e($trip['availability']); ?></td>
                                <td><?php echo e($trip['updated_at']); ?></td>
                                <td class="actions">
                                    <a href="edit_trip.php?id=<?php echo (int)$trip['id']; ?>">Edit</a>
                                    <form method="post" action="delete_trip.php" onsubmit="return confirm('Delete this trip?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                                        <input type="hidden" name="id" value="<?php echo (int)$trip['id']; ?>">
                                        <button type="submit">Delete</button>
                                    </form>
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
