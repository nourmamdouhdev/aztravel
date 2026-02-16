# AZTravel Travel Booking Platform

## Overview
AZTravel is a PHP + MySQL travel booking showcase with three trip categories, a secure admin dashboard, and animated UI.

## Setup
1. Ensure Apache and MySQL are running (XAMPP).
2. Create the database and tables:
   - Import `sql/aztravel.sql` into MySQL (phpMyAdmin or CLI).
   - If you already imported earlier, run these:
     - `ALTER TABLE trips ADD COLUMN details TEXT AFTER itinerary;`
     - `ALTER TABLE trips ADD COLUMN includes TEXT AFTER details;`
     - `ALTER TABLE trips ADD COLUMN excludes TEXT AFTER includes;`
     - `ALTER TABLE trips ADD COLUMN pickup_time VARCHAR(120) AFTER excludes;`
     - `ALTER TABLE trips ADD COLUMN policy TEXT AFTER pickup_time;`
     - `ALTER TABLE users ADD COLUMN full_name VARCHAR(120) AFTER role;`
     - `ALTER TABLE users ADD COLUMN avatar_url VARCHAR(255) AFTER full_name;`
3. Update database credentials in `includes/config.php` if needed.
4. Ensure `uploads/trips` is writable by the web server for image uploads.
5. Ensure `uploads/avatars` is writable by the web server for profile avatars.
4. Visit `http://localhost/aztravel/index.php`.

## Admin Login
- Username: `admin`
- Password: `admin123`
- Role: `manager`

## Pages
- Home: `index.php`
- Religious trips: `religious.php`
- Domestic trips: `domestic.php`
- International trips: `international.php`
- Trip details: `trip.php?id=1`
- Admin login: `login.php`
- Dashboard: `dashboard.php`

## Notes
- Images are loaded from remote URLs (Unsplash).
- Contact form is a safe front-end submission placeholder (no email sent).
- Currency conversion uses static rates in `includes/config.php`. Update them as needed.
