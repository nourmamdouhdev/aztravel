<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

if (!isset($category)) {
    http_response_code(500);
    echo 'Category missing.';
    exit;
}

$search = trim($_GET['q'] ?? '');
$minPrice = trim($_GET['min'] ?? '');
$maxPrice = trim($_GET['max'] ?? '');
$duration = trim($_GET['duration'] ?? '');

$where = ['category = :category'];
$params = ['category' => $category];

if ($search !== '') {
    $where[] = '(name LIKE :search OR description LIKE :search)';
    $params['search'] = '%' . $search . '%';
}
if ($minPrice !== '' && is_numeric($minPrice)) {
    $where[] = 'price >= :minPrice';
    $params['minPrice'] = $minPrice;
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $where[] = 'price <= :maxPrice';
    $params['maxPrice'] = $maxPrice;
}
if ($duration !== '' && ctype_digit($duration)) {
    $where[] = 'duration_days = :duration';
    $params['duration'] = $duration;
}

$sql = 'SELECT * FROM trips WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$trips = $stmt->fetchAll();

require_once __DIR__ . '/header.php';
?>
<section class="category-hero">
    <div class="container">
        <p class="eyebrow">AZTravel · <?php echo ucfirst(e($category)); ?> trips</p>
        <h1><?php echo e($pageTitle); ?></h1>
        <p>Explore curated journeys with expert guidance and full itinerary support.</p>
    </div>
</section>

<section class="filters">
    <div class="container">
        <form class="filter-form" method="get">
            <input type="text" name="q" placeholder="Search trips" value="<?php echo e($search); ?>">
            <input type="number" name="min" placeholder="Min price" value="<?php echo e($minPrice); ?>">
            <input type="number" name="max" placeholder="Max price" value="<?php echo e($maxPrice); ?>">
            <input type="number" name="duration" placeholder="Duration (days)" value="<?php echo e($duration); ?>">
            <button class="btn" type="submit">Filter</button>
            <a class="btn ghost" href="<?php echo e(basename($_SERVER['PHP_SELF'])); ?>">Reset</a>
        </form>
    </div>
</section>

<section class="trip-list">
    <div class="container">
        <div class="trip-grid">
            <?php if (empty($trips)): ?>
                <div class="empty-state">No trips match your filters yet.</div>
            <?php else: ?>
                <?php foreach ($trips as $trip): ?>
                    <article class="trip-card">
                        <?php $image = $trip['image_url'] ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80'; ?>
                        <img src="<?php echo e($image); ?>" alt="<?php echo e($trip['name']); ?>">
                        <div class="trip-card-body">
                            <span class="tag"><?php echo ucfirst(e($trip['category'])); ?></span>
                            <h3><?php echo e($trip['name']); ?></h3>
                            <p><?php echo e($trip['description']); ?></p>
                            <div class="trip-meta">
                                <span><?php echo e($trip['duration_days']); ?> days</span>
                                <span>$<?php echo number_format((float)$trip['price'], 2); ?></span>
                            </div>
                            <?php if (!empty($trip['itinerary'])): ?>
                                <details>
                                    <summary>View itinerary</summary>
                                    <p><?php echo nl2br(e($trip['itinerary'])); ?></p>
                                </details>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>
