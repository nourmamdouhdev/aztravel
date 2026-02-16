<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'AZTravel | Discover Egypt & Beyond';

$contactSuccess = null;
$contactError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    if (!verify_csrf()) {
        $contactError = 'Invalid form submission. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || $email === '' || $message === '') {
            $contactError = 'Please fill in all contact form fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $contactError = 'Please provide a valid email address.';
        } else {
            $contactSuccess = 'Thanks! Our travel team will reply within 24 hours.';
        }
    }
}

$stmt = $pdo->query('SELECT * FROM trips ORDER BY created_at DESC LIMIT 6');
$featuredTrips = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="hero-slides">
        <div class="hero-slide" data-slide="1"></div>
        <div class="hero-slide" data-slide="2"></div>
        <div class="hero-slide" data-slide="3"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-text">
            <p class="eyebrow">AZTravel · Egyptian Travel Experts</p>
            <h1>Journeys that weave faith, history, and wonder.</h1>
            <p>Design your next religious pilgrimage, Nile adventure, or international escape with a team that knows Egypt inside-out.</p>
            <div class="hero-actions">
                <a class="btn" href="#featured">Explore Trips</a>
                <a class="btn ghost" href="#contact">Plan with Us</a>
            </div>
        </div>
        <div class="hero-card">
            <h3>Signature Experiences</h3>
            <div class="hero-badges">
                <div class="hero-badge">
                    <span class="badge-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img">
                            <path d="M12 2.5a6.5 6.5 0 0 1 6.5 6.5c0 4.3-6.5 12-6.5 12S5.5 13.3 5.5 9A6.5 6.5 0 0 1 12 2.5Z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="12" cy="9" r="2.5" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </span>
                    <p>Experts in Siwa & Taba</p>
                </div>
                <div class="hero-badge">
                    <span class="badge-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img">
                            <path d="M4 7h16M7 7v10M12 7v10M17 7v10M4 17h16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <p>Customized trips</p>
                </div>
                <div class="hero-badge">
                    <span class="badge-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img">
                            <path d="M12 3l3.5 7 7.5 1-5.5 5.2 1.4 7.8L12 19l-6.9 4 1.4-7.8L1 11l7.5-1z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p>Adventure</p>
                </div>
                <div class="hero-badge">
                    <span class="badge-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img">
                            <path d="M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M3 10h18M6 10v8h12v-8" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p>Hotels are cheap</p>
                </div>
                <div class="hero-badge">
                    <span class="badge-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img">
                            <path d="M4 15h16M6 15V9h12v6M7 9V7h10v2M8 15v3M16 15v3" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p>Transportation operations</p>
                </div>
                <div class="hero-badge">
                    <span class="badge-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img">
                            <path d="M3 12h18M12 4l4 6h5l-4 3.5L18 20l-6-3.5L6 20l1.5-6.5L3 10h5z" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p>Airplane prices are low</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container feature-grid">
        <div class="feature-card">
            <img src="https://images.unsplash.com/photo-1542359649-31e03cd4d909?auto=format&fit=crop&w=800&q=80" alt="Religious trips">
            <h3>Religious Trips</h3>
            <p>Curated pilgrimages with knowledgeable guides and thoughtful itineraries.</p>
            <a href="religious.php">Browse Trips</a>
        </div>
        <div class="feature-card">
            <img src="https://images.unsplash.com/photo-1513897573398-037cf7c7d63b?auto=format&fit=crop&w=800&q=80" alt="Domestic trips">
            <h3>Domestic Trips</h3>
            <p>From desert oases to the Nile, discover Egypt’s most iconic sites.</p>
            <a href="domestic.php">Browse Trips</a>
        </div>
        <div class="feature-card">
            <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80" alt="International trips">
            <h3>International Trips</h3>
            <p>Global itineraries with the same care and cultural depth.</p>
            <a href="international.php">Browse Trips</a>
        </div>
    </div>
</section>

<section id="featured" class="featured">
    <div class="container">
        <div class="section-header">
            <h2>Featured Trips</h2>
            <p>Hand-picked journeys for every style of traveler.</p>
        </div>
        <div class="trip-grid">
            <?php if (empty($featuredTrips)): ?>
                <div class="empty-state">No trips yet. Admins can add trips from the dashboard.</div>
            <?php else: ?>
                <?php foreach ($featuredTrips as $trip): ?>
                    <article class="trip-card">
                        <?php $image = $trip['image_url'] ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80'; ?>
                        <img src="<?php echo e($image); ?>" alt="<?php echo e($trip['name']); ?>">
                        <div class="trip-card-body">
                            <span class="tag"><?php echo ucfirst(e($trip['category'])); ?></span>
                            <h3><?php echo e($trip['name']); ?></h3>
                            <p><?php echo e($trip['description']); ?></p>
                            <div class="trip-meta">
                                <span><?php echo e($trip['duration_days']); ?> days</span>
                                <span><?php echo format_price((float)$trip['price']); ?></span>
                            </div>
                            <a class="btn ghost small" href="trip.php?id=<?php echo (int)$trip['id']; ?>">View details</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container contact-grid">
        <div class="contact-info">
            <h2>Plan your next journey</h2>
            <p>Share your travel goals and we’ll craft the perfect trip for you.</p>
            <div class="info-cards">
                <div>
                    <h4>Phone</h4>
                    <p>+20 100 123 4567</p>
                </div>
                <div>
                    <h4>Email</h4>
                    <p>hello@aztravel.com</p>
                </div>
                <div>
                    <h4>Office</h4>
                    <p>Downtown Cairo, Egypt</p>
                </div>
            </div>
        </div>
        <form class="contact-form" method="post" action="#contact">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" name="contact_form" value="1">
            <label>
                Full name
                <input type="text" name="name" required>
            </label>
            <label>
                Email address
                <input type="email" name="email" required>
            </label>
            <label>
                Tell us about your trip
                <textarea name="message" rows="4" required></textarea>
            </label>
            <?php if ($contactError): ?>
                <p class="form-error"><?php echo e($contactError); ?></p>
            <?php elseif ($contactSuccess): ?>
                <p class="form-success"><?php echo e($contactSuccess); ?></p>
            <?php endif; ?>
            <button class="btn" type="submit">Send Request</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
