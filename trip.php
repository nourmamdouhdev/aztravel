<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(404);
    echo 'Trip not found.';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM trips WHERE id = :id');
$stmt->execute(['id' => $id]);
$trip = $stmt->fetch();

if (!$trip) {
    http_response_code(404);
    echo 'Trip not found.';
    exit;
}

$pageTitle = $trip['name'] . ' | AZTravel';
$image = $trip['image_url'] ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80';
$whatsAppNumber = '01224560635';
$messageParts = [
    'Hello AZTravel, I want to book this trip:',
    'Trip: ' . $trip['name'],
    'Price: ' . format_price((float)$trip['price']),
    'Duration: ' . $trip['duration_days'] . ' days',
];
if (!empty($trip['details'])) {
    $messageParts[] = 'Details: ' . $trip['details'];
}
$whatsAppText = rawurlencode(implode(' | ', $messageParts));
$whatsAppLink = "https://wa.me/{$whatsAppNumber}?text={$whatsAppText}";

require_once __DIR__ . '/includes/header.php';
?>
<section class="trip-detail reveal">
    <div class="container">
        <a class="back-link" href="<?php echo e($trip['category']); ?>.php">← Back to <?php echo ucfirst(e($trip['category'])); ?> trips</a>
        <div class="trip-detail-grid">
            <div>
                <img src="<?php echo e($image); ?>" alt="<?php echo e($trip['name']); ?>">
            </div>
            <div class="trip-detail-card">
                <span class="tag"><?php echo ucfirst(e($trip['category'])); ?></span>
                <h1><?php echo e($trip['name']); ?></h1>
                <p><?php echo e($trip['description']); ?></p>
                <div class="trip-meta">
                    <span><?php echo e($trip['duration_days']); ?> days</span>
                    <span><?php echo format_price((float)$trip['price']); ?></span>
                    <span>Availability: <?php echo e($trip['availability']); ?></span>
                </div>
                <div class="trip-actions">
                    <a class="btn" href="<?php echo e($whatsAppLink); ?>" target="_blank" rel="noopener">WhatsApp about this trip</a>
                </div>
            </div>
        </div>
        <?php if (!empty($trip['itinerary'])): ?>
            <div class="trip-section reveal">
                <h3>Itinerary</h3>
                <p><?php echo nl2br(e($trip['itinerary'])); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($trip['details'])): ?>
            <div class="trip-section reveal">
                <h3>Trip Details</h3>
                <p><?php echo nl2br(e($trip['details'])); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($trip['includes'])): ?>
            <div class="trip-section reveal">
                <h3>Includes</h3>
                <p><?php echo nl2br(e($trip['includes'])); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($trip['excludes'])): ?>
            <div class="trip-section reveal">
                <h3>Excludes</h3>
                <p><?php echo nl2br(e($trip['excludes'])); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($trip['pickup_time'])): ?>
            <div class="trip-section reveal">
                <h3>Pickup Time</h3>
                <p><?php echo e($trip['pickup_time']); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($trip['policy'])): ?>
            <div class="trip-section">
                <h3>Policy</h3>
                <p><?php echo nl2br(e($trip['policy'])); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
