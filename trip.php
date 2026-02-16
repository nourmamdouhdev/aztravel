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
$whatsAppNumber = '201001234567';
$whatsAppText = rawurlencode('Hello AZTravel, I want details about the trip: ' . $trip['name']);
$whatsAppLink = "https://wa.me/{$whatsAppNumber}?text={$whatsAppText}";

require_once __DIR__ . '/includes/header.php';
?>
<section class="trip-detail">
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
                    <span>$<?php echo number_format((float)$trip['price'], 2); ?></span>
                    <span>Availability: <?php echo e($trip['availability']); ?></span>
                </div>
                <div class="trip-actions">
                    <a class="btn" href="<?php echo e($whatsAppLink); ?>" target="_blank" rel="noopener">WhatsApp about this trip</a>
                </div>
            </div>
        </div>
        <?php if (!empty($trip['itinerary'])): ?>
            <div class="trip-section">
                <h3>Itinerary</h3>
                <p><?php echo nl2br(e($trip['itinerary'])); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($trip['details'])): ?>
            <div class="trip-section">
                <h3>Trip Details</h3>
                <p><?php echo nl2br(e($trip['details'])); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
