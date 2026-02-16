<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Add Trip | AZTravel';
$errors = [];

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
            $stmt = $pdo->prepare('INSERT INTO trips (name, description, price, duration_days, category, image_url, itinerary, availability) VALUES (:name, :description, :price, :duration_days, :category, :image_url, :itinerary, :availability)');
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'duration_days' => $duration,
                'category' => $category,
                'image_url' => $image_url ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80',
                'itinerary' => $itinerary,
                'availability' => $availability === '' ? 0 : (int)$availability,
            ]);

            header('Location: dashboard.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="dashboard">
    <div class="container form-page">
        <h2>Add New Trip</h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <label>
                Trip name *
                <input type="text" name="name" value="<?php echo e($_POST['name'] ?? ''); ?>" required>
            </label>
            <label>
                Category *
                <select name="category" required>
                    <option value="">Select</option>
                    <option value="religious" <?php echo (($_POST['category'] ?? '') === 'religious') ? 'selected' : ''; ?>>Religious</option>
                    <option value="domestic" <?php echo (($_POST['category'] ?? '') === 'domestic') ? 'selected' : ''; ?>>Domestic</option>
                    <option value="international" <?php echo (($_POST['category'] ?? '') === 'international') ? 'selected' : ''; ?>>International</option>
                </select>
            </label>
            <label>
                Price (USD) *
                <input type="number" step="0.01" name="price" value="<?php echo e($_POST['price'] ?? ''); ?>" required>
            </label>
            <label>
                Duration (days) *
                <input type="number" name="duration_days" value="<?php echo e($_POST['duration_days'] ?? ''); ?>" required>
            </label>
            <label class="full">
                Description *
                <textarea name="description" rows="3" required><?php echo e($_POST['description'] ?? ''); ?></textarea>
            </label>
            <label class="full">
                Itinerary
                <textarea name="itinerary" rows="4"><?php echo e($_POST['itinerary'] ?? ''); ?></textarea>
            </label>
            <label class="full">
                Image URL
                <input type="url" name="image_url" value="<?php echo e($_POST['image_url'] ?? ''); ?>">
            </label>
            <label>
                Availability
                <input type="number" name="availability" value="<?php echo e($_POST['availability'] ?? ''); ?>">
            </label>
            <?php if (!empty($errors)): ?>
                <div class="form-error full">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <button class="btn" type="submit">Create Trip</button>
            <a class="btn ghost" href="dashboard.php">Cancel</a>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
