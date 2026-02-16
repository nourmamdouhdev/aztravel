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
        $itinerary = trim($_POST['itinerary'] ?? '');
        $availability = trim($_POST['availability'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $includes = trim($_POST['includes'] ?? '');
        $excludes = trim($_POST['excludes'] ?? '');
        $pickup_time = trim($_POST['pickup_time'] ?? '');
        $policy = trim($_POST['policy'] ?? '');

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

        $uploadedImage = upload_trip_image($_FILES['image_file'] ?? [], $errors);
        if (!$uploadedImage && empty($errors)) {
            $errors[] = 'Please upload a trip image.';
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare('INSERT INTO trips (name, description, price, duration_days, category, image_url, itinerary, details, includes, excludes, pickup_time, policy, availability) VALUES (:name, :description, :price, :duration_days, :category, :image_url, :itinerary, :details, :includes, :excludes, :pickup_time, :policy, :availability)');
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'duration_days' => $duration,
                'category' => $category,
                'image_url' => $uploadedImage,
                'itinerary' => $itinerary,
                'details' => $details,
                'includes' => $includes,
                'excludes' => $excludes,
                'pickup_time' => $pickup_time,
                'policy' => $policy,
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
        <form method="post" class="form-grid" enctype="multipart/form-data">
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
                Details
                <textarea name="details" rows="4"><?php echo e($_POST['details'] ?? ''); ?></textarea>
            </label>
            <label class="full">
                Includes
                <textarea name="includes" rows="3"><?php echo e($_POST['includes'] ?? ''); ?></textarea>
            </label>
            <label class="full">
                Excludes
                <textarea name="excludes" rows="3"><?php echo e($_POST['excludes'] ?? ''); ?></textarea>
            </label>
            <label>
                Pickup Time
                <input type="text" name="pickup_time" value="<?php echo e($_POST['pickup_time'] ?? ''); ?>">
            </label>
            <label class="full">
                Policy
                <textarea name="policy" rows="3"><?php echo e($_POST['policy'] ?? ''); ?></textarea>
            </label>
            <label class="full">
                Trip Image (JPG/PNG/WEBP, max 4MB)
                <input type="file" name="image_file" accept="image/*" required>
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
