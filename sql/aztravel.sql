CREATE DATABASE IF NOT EXISTS aztravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aztravel;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'admin',
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
  availability INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, password_hash) VALUES
('admin', '$2y$12$Efi0QywAVNGLLmfwLqb2XOcag2J9nsFtwV/z6uFBCblljp6IxCG9a');

INSERT INTO trips (name, description, price, duration_days, category, image_url, itinerary, availability) VALUES
('Cairo & Holy Sites', 'Visit Cairo’s spiritual landmarks with expert guides.', 480.00, 4, 'religious', 'https://images.unsplash.com/photo-1544986581-efac024faf62?auto=format&fit=crop&w=800&q=80', 'Day 1: Arrival and mosque tour. Day 2: Coptic Cairo. Day 3: Guided reflections. Day 4: Departure.', 18),
('Nile Discovery', 'Cruise the Nile and explore Luxor and Aswan.', 920.00, 6, 'domestic', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80', 'Day 1: Cairo. Day 2-4: Nile cruise. Day 5: Aswan. Day 6: Return.', 22),
('Mediterranean Escape', 'A week-long international journey with coastal highlights.', 1350.00, 7, 'international', 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80', 'Day 1: Departure. Day 2-6: Coastal tours. Day 7: Return.', 12);
