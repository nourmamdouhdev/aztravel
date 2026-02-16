CREATE DATABASE IF NOT EXISTS aztravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aztravel;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'staff',
  full_name VARCHAR(120) DEFAULT NULL,
  avatar_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS trips (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  duration_days INT NOT NULL,
  category ENUM('religious','domestic','international') NOT NULL,
  image_url VARCHAR(255) DEFAULT NULL,
  itinerary TEXT,
  details TEXT,
  includes TEXT,
  excludes TEXT,
  pickup_time VARCHAR(120),
  policy TEXT,
  availability INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, password_hash, role) VALUES
('admin', '$2y$12$Efi0QywAVNGLLmfwLqb2XOcag2J9nsFtwV/z6uFBCblljp6IxCG9a', 'manager');

INSERT INTO trips (name, description, price, duration_days, category, image_url, itinerary, details, includes, excludes, pickup_time, policy, availability) VALUES
('Cairo & Holy Sites', 'Visit Cairo’s spiritual landmarks with expert guides.', 480.00, 4, 'religious', 'https://images.unsplash.com/photo-1544986581-efac024faf62?auto=format&fit=crop&w=800&q=80', 'Day 1: Arrival and mosque tour. Day 2: Coptic Cairo. Day 3: Guided reflections. Day 4: Departure.', 'Includes: expert guide, transfers, daily breakfast. Highlights: Old Cairo, Al-Azhar, Coptic churches.', 'Guide, transfers, breakfast', 'Personal expenses, tips', '08:00 AM from hotel lobby', 'Free cancel up to 48 hours before.', 18),
('Nile Discovery', 'Cruise the Nile and explore Luxor and Aswan.', 920.00, 6, 'domestic', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80', 'Day 1: Cairo. Day 2-4: Nile cruise. Day 5: Aswan. Day 6: Return.', 'Includes: 5-star cruise, entrance tickets, private guide. Highlights: Karnak, Valley of the Kings.', 'Cruise, tickets, guide', 'Flights, personal expenses', '09:00 AM from Cairo', 'Full refund up to 7 days before.', 22),
('Mediterranean Escape', 'A week-long international journey with coastal highlights.', 1350.00, 7, 'international', 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80', 'Day 1: Departure. Day 2-6: Coastal tours. Day 7: Return.', 'Includes: flights, boutique stays, local experiences. Highlights: coastal walks, food tours.', 'Flights, hotels, local guide', 'Visa fees, meals not mentioned', 'Meet at airport 3 hours before flight', 'Non-refundable after confirmation.', 12);
