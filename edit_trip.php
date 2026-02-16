<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Edit Trip | AZTravel';
$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare('SELECT * FROM trips WHERE id = :id');
$stmt->execute(['id' => $id]);
$trip = $stmt->fetch();

if (!$trip) {
    http_response_code(404);
    echo 'Trip not found.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $duration = trim($_POST['duration_days'] ?? '');
        $category = $_POST['category'] ?? '';
        $image_url = trim($_POST['image_url'] ?? '');
        $itinerary = trim($_POST['itinerary'] ?? '');
        $availability = trim($_POST['availability'] ?? '');
        $details = trim($_POST['details'] ?? '');

        if ($name === '' || $description === '' || $price === '' || $duration === '' || $category === '') {
            $errors[] = 'Please fill in all required fields.';
        }

        if (!is_numeric($price) || (float)$price < 0) {
            $errors[] = 'Price must be a positive number.';
        }

        if (!ctype_digit($duration) || (int)$duration <= 0) {
            $errors[] = 'Duration must be a positive number of days.';
        }

        if ($availability !== '' && (!ctype_digit($availability) || (int)$availability < 0)) {
            $errors[] = 'Availability must be a positive number.';
        }

        $allowedCategories = ['religious', 'domestic', 'international'];
        if (!in_array($category, $allowedCategories, true)) {
            $errors[] = 'Invalid category selected.';
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare('UPDATE trips SET name = :name, description = :description, price = :price, duration_days = :duration_days, category = :category, image_url = :image_url, itinerary = :itinerary, details = :details, availability = :availability WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'duration_days' => $duration,
                'category' => $category,
                'image_url' => $image_url,
                'itinerary' => $itinerary,
                'details' => $details,
                'availability' => $availability === '' ? 0 : (int)$availability,
                'id' => $id,
            ]);

            header('Location: dashboard.php');
            exit;
        }

        $trip = array_merge($trip, [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'duration_days' => $duration,
            'category' => $category,
            'image_url' => $image_url,
            'itinerary' => $itinerary,
            'details' => $details,
            'availability' => $availability,
        ]);
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard">
    <div class="container form-page">
        <h2>Edit Trip</h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <label>
                Trip name *
                <input type="text" name="name" value="<?php echo e($trip['name']); ?>" required>
            </label>
            <label>
                Category *
                <select name="category" required>
                    <option value="religious" <?php echo ($trip['category'] === 'religious') ? 'selected' : ''; ?>>Religious</option>
                    <option value="domestic" <?php echo ($trip['category'] === 'domestic') ? 'selected' : ''; ?>>Domestic</option>
                    <option value="international" <?php echo ($trip['category'] === 'international') ? 'selected' : ''; ?>>International</option>
                </select>
            </label>
            <label>
                Price (USD) *
                <input type="number" step="0.01" name="price" value="<?php echo e($trip['price']); ?>" required>
            </label>
            <label>
                Duration (days) *
                <input type="number" name="duration_days" value="<?php echo e($trip['duration_days']); ?>" required>
            </label>
            <label class="full">
                Description *
                <textarea name="description" rows="3" required><?php echo e($trip['description']); ?></textarea>
            </label>
            <label class="full">
                Itinerary
                <textarea name="itinerary" rows="4"><?php echo e($trip['itinerary']); ?></textarea>
            </label>
            <label class="full">
                Details
                <textarea name="details" rows="4"><?php echo e($trip['details'] ?? ''); ?></textarea>
            </label>
            <label class="full">
                Image URL
                <input type="url" name="image_url" value="<?php echo e($trip['image_url']); ?>">
            </label>
            <label>
                Availability
                <input type="number" name="availability" value="<?php echo e($trip['availability']); ?>">
            </label>
            <?php if (!empty($errors)): ?>
                <div class="form-error full">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <button class="btn" type="submit">Save Changes</button>
            <a class="btn ghost" href="dashboard.php">Cancel</a>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
